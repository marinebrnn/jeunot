<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Event;

use App\Application\CommandBusInterface;
use App\Application\Event\Command\RegisterUserForAnEventCommand;
use App\Application\Event\Query\GetDetailedEventQuery;
use App\Application\QueryBusInterface;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Exception\UserCannotRegisterToEventException;
use App\Infrastructure\Form\Event\RegisterFormType;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class RegisterUserForAnEventController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
        private FormFactoryInterface $formFactory,
        private UrlGeneratorInterface $urlGenerator,
        private AuthenticatedUser $authenticatedUser,
        private CommandBusInterface $commandBus,
        private TranslatorInterface $translator,
    ) {
    }

    #[Route(
        '/events/{uuid}/register',
        name: 'app_event_register',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['GET', 'POST'],
    )]
    public function __invoke(Request $request, string $uuid): Response
    {
        try {
            $event = $this->queryBus->handle(new GetDetailedEventQuery($uuid));
        } catch (EventNotFoundException) {
            throw new NotFoundHttpException();
        }

        /** @var FlashBagAwareSessionInterface */
        $session = $request->getSession();
        $command = new RegisterUserForAnEventCommand($uuid, $this->authenticatedUser->getUser());
        $form = $this->formFactory->create(RegisterFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($command);
            } catch (UserCannotRegisterToEventException) {
                $session->getFlashBag()->add('error', $this->translator->trans('events.register.failed'));

                return new RedirectResponse($this->urlGenerator->generate('app_event_show', ['uuid' => $uuid]));
            }

            return new RedirectResponse($this->urlGenerator->generate('app_event_register_succeeded', ['uuid' => $uuid]));
        }

        return new Response(
            content: $this->twig->render(
                name: 'events/register.html.twig',
                context : [
                    'event' => $event,
                    'form' => $form->createView(),
                ],
            ),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Event;

use App\Application\CommandBusInterface;
use App\Application\Event\Command\UnregisterUserForAnEventCommand;
use App\Domain\Event\Exception\AttendeeNotFoundException;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UnregisterUserForAnEventController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private AuthenticatedUser $authenticatedUser,
        private UrlGeneratorInterface $urlGenerator,
        private CsrfTokenManagerInterface $csrfTokenManager,
        private TranslatorInterface $translator,
    ) {
    }

    #[Route(
        '/events/{uuid}/unregister',
        name: 'app_events_unregister',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['POST'],
    )]
    public function __invoke(Request $request, string $uuid): Response
    {
        $csrfToken = new CsrfToken('unregister-user-for-an-event', $request->request->get('token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }

        /** @var FlashBagAwareSessionInterface */
        $session = $request->getSession();
        $loggedUser = $this->authenticatedUser->getUser();

        try {
            $this->commandBus->handle(new UnregisterUserForAnEventCommand($uuid, $loggedUser));

            $session->getFlashBag()->add('success', $this->translator->trans('events.cancel_registration.success'));
        } catch (EventNotFoundException|AttendeeNotFoundException) {
            throw new NotFoundHttpException();
        }

        return new RedirectResponse($this->urlGenerator->generate('app_events_detail', ['uuid' => $uuid]));
    }
}

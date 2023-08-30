<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Register;

use App\Application\CommandBusInterface;
use App\Application\User\Command\RegisterUserCommand;
use App\Application\User\Command\SendConfirmationMailCommand;
use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Infrastructure\Form\User\RegisterUserFormType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class RegisterController
{
    public function __construct(
        private \Twig\Environment $twig,
        private FormFactoryInterface $formFactory,
        private CommandBusInterface $commandBus,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $registerCommand = new RegisterUserCommand();
        $form = $this->formFactory->create(RegisterUserFormType::class, $registerCommand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->commandBus->handle($registerCommand);
                $this->commandBus->dispatchAsync(new SendConfirmationMailCommand($user->getEmail()));

                return new RedirectResponse(
                    $this->urlGenerator->generate('app_register_succeeded', ['email' => $user->getEmail()]),
                );
            } catch (UserAlreadyRegisteredException) {
                $form->get('email')->addError(
                    new FormError(
                        $this->translator->trans('login.error.user_already_registered', [], 'validators'),
                    ),
                );
            }
        }

        return new Response(
            content: $this->twig->render(
                name: 'register/index.html.twig',
                context: [
                    'form' => $form->createView(),
                ],
            ),
            status: ($form->isSubmitted() && !$form->isValid())
                ? Response::HTTP_UNPROCESSABLE_ENTITY
                : Response::HTTP_OK,
        );
    }
}

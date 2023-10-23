<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\App\Profile;

use App\Application\CommandBusInterface;
use App\Application\User\Command\ChangePasswordCommand;
use App\Domain\User\Exception\PasswordDoesntMatchException;
use App\Infrastructure\Form\User\ChangePasswordFormType;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class EditPasswordController
{
    public function __construct(
        private \Twig\Environment $twig,
        private FormFactoryInterface $formFactory,
        private CommandBusInterface $commandBus,
        private TranslatorInterface $translator,
        private UrlGeneratorInterface $urlGenerator,
        private AuthenticatedUser $authenticatedUser,
    ) {
    }

    #[Route('/profile/edit/password', name: 'app_profile_edit_password', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $user = $this->authenticatedUser->getUser();
        $command = new ChangePasswordCommand($user->getUuid());
        $form = $this->formFactory->create(ChangePasswordFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $this->commandBus->handle($command);
                $this->authenticatedUser->getSymfonyUser()->update($user);

                return new RedirectResponse(
                    $this->urlGenerator->generate('app_profile_detail', ['uuid' => $user->getUuid()]),
                );
            } catch (PasswordDoesntMatchException) {
                $form->get('oldPassword')->addError(
                    new FormError(
                        $this->translator->trans('profile.error.old_password', [], 'validators'),
                    ),
                );
            }
        }

        return new Response(
            content: $this->twig->render(
                name: 'app/profile/password.html.twig',
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

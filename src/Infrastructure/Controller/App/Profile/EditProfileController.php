<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\App\Profile;

use App\Application\CommandBusInterface;
use App\Application\User\Command\UpdateProfileCommand;
use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Infrastructure\Form\User\ProfileFormType;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class EditProfileController
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

    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $user = $this->authenticatedUser->getUser();
        $command = new UpdateProfileCommand($this->authenticatedUser->getUser());
        $form = $this->formFactory->create(ProfileFormType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->handle($command);

                return new RedirectResponse(
                    $this->urlGenerator->generate('app_profile_detail', ['uuid' => $user->getUuid()]),
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
                name: 'app/profile/edit.html.twig',
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

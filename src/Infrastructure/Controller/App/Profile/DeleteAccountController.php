<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\App\Profile;

use App\Application\CommandBusInterface;
use App\Application\User\Command\DeleteUserAccountCommand;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class DeleteAccountController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private AuthenticatedUser $authenticatedUser,
        private Security $security,
        private UrlGeneratorInterface $urlGenerator,
        private CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

    #[Route(
        '/profile/delete',
        name: 'app_profile_delete',
        methods: ['POST'],
    )]
    public function __invoke(Request $request): RedirectResponse
    {
        $csrfToken = new CsrfToken('delete-user-account', $request->request->get('token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }

        $user = $this->authenticatedUser->getUser();
        $this->commandBus->handle(new DeleteUserAccountCommand($user->getUuid()));
        $this->security->logout(false);

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}

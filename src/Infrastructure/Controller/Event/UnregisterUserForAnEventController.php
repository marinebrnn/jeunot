<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Event;

use App\Application\CommandBusInterface;
use App\Application\Event\Command\UnregisterUserForAnEventCommand;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class UnregisterUserForAnEventController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private AuthenticatedUser $authenticatedUser,
        private UrlGeneratorInterface $urlGenerator,
        private CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

    #[Route(
        '/events/{uuid}/unregister',
        name: 'app_event_unregister',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['DELETE'],
    )]
    public function __invoke(Request $request, string $uuid): Response
    {
        $loggedUser = $this->authenticatedUser->getUser();
        $command = new UnregisterUserForAnEventCommand($uuid,$loggedUser);

        $csrfToken = new CsrfToken('UnregisterUserForAnEvent', $request->request->get('token'));
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }

        try {
            $this->commandBus->handle($command);
        } catch(EventNotFoundException) {
            throw new NotFoundHttpException();
        }

        return new RedirectResponse
        ($this->urlGenerator->generate
        ('app_event_show', ['uuid' => $uuid]));

    }
}


<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony\EventSubscriber;

use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class LoggedUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AuthenticatedUser $authenticatedUser,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 0],
            ],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $routeName = $event->getRequest()->attributes->get('_route');

        if (!\in_array($routeName, ['app_login', 'app_register'])) {
            return;
        }

        if ($this->authenticatedUser->getUser()) {
            $event->setResponse(
                new RedirectResponse($this->urlGenerator->generate('app_dashboard')),
            );
        }
    }
}

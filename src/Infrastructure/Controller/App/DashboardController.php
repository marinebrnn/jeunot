<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\App;

use App\Application\Event\Query\GetActiveEventsQuery;
use App\Application\Post\Query\GetPublishedPostsQuery;
use App\Application\QueryBusInterface;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
        private AuthenticatedUser $authenticatedUser,
    ) {
    }

    #[Route(name: 'app_dashboard', methods: ['GET'])]
    public function __invoke(): Response
    {
        $loggedUserUuid = $this->authenticatedUser->getUser()->getUuid();
        $paginatedEvents = $this->queryBus->handle(new GetActiveEventsQuery(1, 6, $loggedUserUuid, true));
        $paginatedPosts = $this->queryBus->handle(new GetPublishedPostsQuery(1, 3));

        return new Response($this->twig->render(
            name: 'app/index.html.twig',
            context : [
                'paginatedEvents' => $paginatedEvents,
                'paginatedPosts' => $paginatedPosts,
            ],
        ));
    }
}

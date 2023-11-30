<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Event;

use App\Application\Event\Query\GetActiveEventsQuery;
use App\Application\Event\Query\GetDetailedEventQuery;
use App\Application\QueryBusInterface;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Infrastructure\Security\AuthenticatedUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final readonly class DetailEventController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
        private AuthenticatedUser $authenticatedUser,
    ) {
    }

    #[Route(
        '/events/{uuid}',
        name: 'app_events_detail',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['GET'],
    )]
    public function __invoke(string $uuid): Response
    {
        try {
            $loggedUserUuid = $this->authenticatedUser->getUser()?->getUuid();
            $event = $this->queryBus->handle(new GetDetailedEventQuery($uuid, $loggedUserUuid));
        } catch (EventNotFoundException) {
            throw new NotFoundHttpException();
        }

        $otherEvents = $this->queryBus->handle(
            new GetActiveEventsQuery(
                page: 1,
                pageSize: 2,
                loggedUserUuid: $loggedUserUuid,
                excludeUuid: $uuid,
            ),
        );

        return new Response(
            content: $this->twig->render(
                name: 'events/detail.html.twig',
                context: [
                    'event' => $event,
                    'otherEvents' => $otherEvents->items,
                ],
            ),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Event;

use App\Application\Event\Query\GetAttendeesByEventQuery;
use App\Application\QueryBusInterface;
use App\Domain\Event\Exception\EventNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class GetAttendeesByEventController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route(
        path: '/events/{uuid}/attendees',
        name: 'app_event_attendees',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['GET'],
    )]
    public function __invoke(string $uuid): Response
    {
        try {
            $attendees = $this->queryBus->handle(new GetAttendeesByEventQuery($uuid));
        } catch (EventNotFoundException) {
            throw new NotFoundHttpException('');
        }

        return new Response(
            content: $this->twig->render(
                name: 'events/attendees.html.twig',
                context : [
                    'attendees' => $attendees,
                    'event' => $uuid,
                ],
            ),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Event;

use App\Application\Event\Query\GetEventByUuidQuery;
use App\Application\QueryBusInterface;
use App\Domain\Event\Exception\EventNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final readonly class ShowEventController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route(
        '/events/{uuid}',
        name: 'app_event_show',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['GET'],
    )]
    public function __invoke(string $uuid): Response
    {
        try {
            $event = $this->queryBus->handle(new GetEventByUuidQuery($uuid));
        } catch (EventNotFoundException) {
            throw new NotFoundHttpException();
        }

        return new Response(
            content: $this->twig->render(
                name: 'events/show.html.twig',
                context : [
                    'event' => $event,
                ],
            ),
        );
    }
}

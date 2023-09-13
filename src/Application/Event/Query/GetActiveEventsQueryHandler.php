<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\Event\View\EventSummaryView;
use App\Domain\Event\Repository\EventRepositoryInterface;
use App\Domain\Pagination;

final class GetActiveEventsQueryHandler
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
    ) {
    }

    public function __invoke(GetActiveEventsQuery $query): Pagination
    {
        ['events' => $events, 'count' => $count] = $this->eventRepository->findActiveEvents(
            $query->pageSize,
            $query->page,
        );

        $views = [];
        foreach ($events as $event) {
            $views[] = new EventSummaryView(
                uuid: $event['uuid'],
                title: $event['title'],
                location: $event['location'],
                nbAttendees: $event['nbAttendees'],
                startDate: $event['startDate'],
                picture: $event['picture'],
            );
        }

        return new Pagination($views, $count, $query->page, $query->pageSize);
    }
}

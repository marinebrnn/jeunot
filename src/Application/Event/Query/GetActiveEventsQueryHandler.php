<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\Event\View\SummarizedEventView;
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
            $query->loggedUserUuid,
        );

        $views = [];
        foreach ($events as $event) {
            $views[] = new SummarizedEventView(
                uuid: $event['uuid'],
                title: $event['title'],
                location: $event['location'],
                nbAttendees: $event['nbAttendees'],
                startDate: $event['startDate'],
                isLoggedUserRegisteredForEvent: !empty($event['isLoggedUserRegisteredForEvent']) ? true : false,
                picture: $event['picture'],
            );
        }

        return new Pagination($views, $count, $query->page, $query->pageSize);
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\Event\View\EventDetailView;
use App\Application\Event\View\OwnerView;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\EventRepositoryInterface;

final class GetEventByUuidQueryHandler
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
    ) {
    }

    public function __invoke(GetEventByUuidQuery $query): EventDetailView
    {
        $event = $this->eventRepository->findOneByUuid($query->uuid);
        if (!$event) {
            throw new EventNotFoundException();
        }

        $event = current($event);

        return new EventDetailView(
            uuid: $event['uuid'],
            title: $event['title'],
            description: $event['description'],
            location: $event['location'],
            nbAttendees: $event['nbAttendees'],
            nbAvailablePlaces: $event['initialAvailablePlaces'] - $event['nbAttendees'],
            startDate: $event['startDate'],
            endDate: $event['endDate'],
            owner: new OwnerView($event['ownerFirstName']),
            picture: $event['picture'],
        );
    }
}

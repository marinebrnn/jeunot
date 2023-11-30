<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\DateUtilsInterface;
use App\Application\Event\View\DetailedEventView;
use App\Application\Event\View\OwnerView;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\EventRepositoryInterface;

final class GetDetailedEventQueryHandler
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private DateUtilsInterface $dateUtils,
    ) {
    }

    public function __invoke(GetDetailedEventQuery $query): DetailedEventView
    {
        $event = $this->eventRepository->findDetailedEvent($query->uuid, $query->loggedUserUuid);
        if (!$event) {
            throw new EventNotFoundException();
        }

        $event = current($event);
        $ownerAge = $event['ownerDisplayMyAge'] ? \DateTime::createFromInterface($event['ownerBirthday'])->diff($this->dateUtils->getNow())->y : null;
        $startDate = \DateTime::createFromInterface($event['startDate']);
        $endDate = \DateTime::createFromInterface($event['endDate']);
        $isOverSeveralDays = $this->dateUtils->getDaysInterval($startDate, $endDate) > 0;

        return new DetailedEventView(
            uuid: $event['uuid'],
            title: $event['title'],
            description: $event['description'],
            location: $event['location'],
            nbAttendees: $event['nbAttendees'],
            nbAvailablePlaces: $event['initialAvailablePlaces'] - $event['nbAttendees'],
            startDate: $startDate,
            endDate: $endDate,
            owner: new OwnerView(
                uuid: $event['ownerUuid'],
                firstName: $event['ownerFirstName'],
                age: $ownerAge,
                city: $event['ownerCity'],
                avatar: $event['ownerAvatar'],
            ),
            isLoggedUserRegisteredForEvent: !empty($event['isLoggedUserRegisteredForEvent']) ? true : false,
            picture: $event['picture'],
            isOverSeveralDays: $isOverSeveralDays,
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\Event\View\SummarizedAttendeesView;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\Event\Repository\EventRepositoryInterface;

final class GetAttendeesByEventQueryHandler
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private AttendeeRepositoryInterface $attendeeRepository,
    ) {
    }

    public function __invoke(GetAttendeesByEventQuery $query): array
    {
        $event = $this->eventRepository->findOneByUuid($query->uuid);
        if (!$event) {
            throw new EventNotFoundException();
        }

        $views = [];
        $attendees = $this->attendeeRepository->findAttendeesByEvent($event);

        foreach ($attendees as $attendee) {
            $views[] = new SummarizedAttendeesView(
                firstName: $attendee['firstName'],
                lastName: $attendee['lastName'],
            );
        }

        return $views;
    }
}

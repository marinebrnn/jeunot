<?php

declare(strict_types=1);

namespace App\Application\Event\Command;

use App\Domain\Event\Event;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\Event\Repository\EventRepositoryInterface;
use App\Domain\User\Exception\UserNotFoundException;

final class UnregisterUserForAnEventCommandHandler
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private AttendeeRepositoryInterface $attendeeRepository,
        
    ){}

    public function __invoke(UnregisterUserForAnEventCommand $command): void
    {
        $event = $this->eventRepository->findOneByUuid($command->eventUuid);
        if(!$event instanceof Event)
        {
            throw new EventNotFoundException;
        }
        
        $attendee = $this->attendeeRepository->findByEventAndUser($event,$command->loggedUser);
        if($attendee === null)
        {
            throw new UserNotFoundException;
        }
        
            $this->attendeeRepository->delete($attendee);
        
    }
}
<?php

declare(strict_types=1);

namespace App\Domain\Event\Specification;

use App\Domain\Event\Event;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\User\User;

final class CanUserRegisterToEvent
{
    public function __construct(
        private readonly AttendeeRepositoryInterface $attendeeRepository,
    ) {
    }

    public function isSatisfiedBy(Event $event, User $user): bool
    {
        if ($this->attendeeRepository->countByEvent($event) >= $event->getInitialAvailablePlaces()) {
            return false;
        }

        if ($this->attendeeRepository->countByEventAndUser($event, $user) > 0) {
            return false;
        }

        return true;
    }
}

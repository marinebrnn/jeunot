<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\User\User;

class Attendee
{
    public function __construct(
        private string $uuid,
        private User $user,
        private Event $event,
        private \DateTimeInterface $registeredOn,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getRegisteredOn(): \DateTimeInterface
    {
        return $this->registeredOn;
    }
}

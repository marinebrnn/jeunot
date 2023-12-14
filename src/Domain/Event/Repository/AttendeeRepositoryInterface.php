<?php

declare(strict_types=1);

namespace App\Domain\Event\Repository;

use App\Domain\Event\Attendee;
use App\Domain\Event\Event;
use App\Domain\User\User;

interface AttendeeRepositoryInterface
{
    public function add(Attendee $attendee): void;

    public function countByEvent(Event $event): int;

    public function countByEventAndUser(Event $event, User $user): int;

    public function findOneByEventAndUser(Event $event, User $user): ?Attendee;

    public function findAttendeesByEvent(Event $event): ?array;

    public function delete(Attendee $attendee): void;
}

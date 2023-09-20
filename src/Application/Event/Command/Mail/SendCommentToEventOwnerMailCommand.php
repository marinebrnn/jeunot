<?php

declare(strict_types=1);

namespace App\Application\Event\Command\Mail;

use App\Application\AsyncCommandInterface;
use App\Domain\Event\Event;
use App\Domain\User\User;

final readonly class SendCommentToEventOwnerMailCommand implements AsyncCommandInterface
{
    public function __construct(
        public Event $event,
        public User $attendee,
        public string $comment,
    ) {
    }
}

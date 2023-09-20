<?php

declare(strict_types=1);

namespace App\Application\Event\Command\Mail;

use App\Application\AsyncCommandInterface;
use App\Domain\Event\Event;

final readonly class SendUserRegistrationConfirmationMailCommand implements AsyncCommandInterface
{
    public function __construct(
        public string $email,
        public Event $event,
    ) {
    }
}

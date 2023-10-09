<?php

declare(strict_types=1);

namespace App\Application\Event\Command;

use App\Application\CommandInterface;
use App\Domain\User\User;

final readonly class UnregisterUserForAnEventCommand implements CommandInterface
{
    public function __construct(
        public string $eventUuid,
        public User $loggedUser,
    ) {
    }
}

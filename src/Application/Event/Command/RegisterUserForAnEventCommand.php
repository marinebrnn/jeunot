<?php

declare(strict_types=1);

namespace App\Application\Event\Command;

use App\Application\CommandInterface;
use App\Domain\User\User;

final class RegisterUserForAnEventCommand implements CommandInterface
{
    public ?string $comment = '';

    public function __construct(
        public readonly string $eventUuid,
        public readonly User $user,
    ) {
    }
}

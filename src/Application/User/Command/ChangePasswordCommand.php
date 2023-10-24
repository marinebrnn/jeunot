<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\CommandInterface;

final class ChangePasswordCommand implements CommandInterface
{
    public ?string $oldPassword = '';
    public ?string $newPassword = '';

    public function __construct(
        public readonly string $uuid,
    ) {
    }
}

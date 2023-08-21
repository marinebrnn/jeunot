<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\AsyncCommandInterface;

final class SendConfirmationLinkCommand implements AsyncCommandInterface
{
    public function __construct(public readonly string $email)
    {
    }
}

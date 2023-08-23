<?php

declare(strict_types=1);

namespace App\Application\Mail\User;

interface ConfirmAccountMailInterface
{
    public function send(string $email, \DateTimeInterface $expirationDate): void;
}

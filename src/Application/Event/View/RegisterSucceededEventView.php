<?php

declare(strict_types=1);

namespace App\Application\Event\View;

final readonly class RegisterSucceededEventView
{
    public function __construct(
        public string $uuid,
        public string $title,
        public string $ownerEmail,
    ) {
    }
}

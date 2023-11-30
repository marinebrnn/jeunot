<?php

declare(strict_types=1);

namespace App\Application\Event\View;

final readonly class OwnerView
{
    public function __construct(
        public string $uuid,
        public string $firstName,
        public ?int $age,
        public ?string $city,
        public ?string $avatar,
    ) {
    }
}

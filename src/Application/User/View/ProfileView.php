<?php

declare(strict_types=1);

namespace App\Application\User\View;

final readonly class ProfileView
{
    public function __construct(
        public string $username,
        public ?string $city,
        public ?string $biography,
        public ?int $age,
        public ?\DateTimeInterface $registrationDate,
    ) {
    }
}

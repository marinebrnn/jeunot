<?php

declare(strict_types=1);

namespace App\Application\Event\View;

final readonly class OwnerView
{
    public function __construct(
        public string $firstName,
    ) {
    }
}

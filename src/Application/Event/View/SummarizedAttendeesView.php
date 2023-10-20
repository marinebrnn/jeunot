<?php

declare(strict_types=1);

namespace App\Application\Event\View;

final readonly class SummarizedAttendeesView
{
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {
    }
}

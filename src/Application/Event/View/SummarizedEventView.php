<?php

declare(strict_types=1);

namespace App\Application\Event\View;

final readonly class SummarizedEventView
{
    public function __construct(
        public string $uuid,
        public string $title,
        public string $location,
        public int $nbAttendees,
        public \DateTimeInterface $startDate,
        public ?string $picture,
    ) {
    }
}

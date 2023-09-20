<?php

declare(strict_types=1);

namespace App\Application\Event\View;

final readonly class DetailedEventView
{
    public function __construct(
        public string $uuid,
        public string $title,
        public string $description,
        public string $location,
        public int $nbAttendees,
        public int $nbAvailablePlaces,
        public \DateTimeInterface $startDate,
        public \DateTimeInterface $endDate,
        public OwnerView $owner,
        public ?string $picture,
    ) {
    }
}

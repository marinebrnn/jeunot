<?php

declare(strict_types=1);

namespace App\Application\Post\View;

final readonly class SummarizedPostView
{
    public function __construct(
        public string $slug,
        public string $title,
        public \DateTimeInterface $publicationDate,
        public ?string $picture,
    ) {
    }
}

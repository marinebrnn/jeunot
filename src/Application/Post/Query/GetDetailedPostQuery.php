<?php

declare(strict_types=1);

namespace App\Application\Post\Query;

use App\Application\QueryInterface;

final class GetDetailedPostQuery implements QueryInterface
{
    public function __construct(
        public readonly string $slug,
    ) {
    }
}

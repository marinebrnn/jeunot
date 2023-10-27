<?php

declare(strict_types=1);

namespace App\Application\Post\Query;

use App\Application\QueryInterface;

final readonly class GetPublishedPostsQuery implements QueryInterface
{
    public function __construct(
        public int $page,
        public int $pageSize,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\QueryInterface;

final class GetActiveEventsQuery implements QueryInterface
{
    public function __construct(
        public readonly int $page,
        public readonly int $pageSize,
    ) {
    }
}

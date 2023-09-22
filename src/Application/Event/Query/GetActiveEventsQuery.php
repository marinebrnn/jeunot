<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\QueryInterface;

final readonly class GetActiveEventsQuery implements QueryInterface
{
    public function __construct(
        public int $page,
        public int $pageSize,
        public ?string $loggedUserUuid = null,
    ) {
    }
}

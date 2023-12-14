<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\QueryInterface;

final class GetAttendeesByEventQuery implements QueryInterface
{
    public function __construct(
        public readonly string $uuid,
    ) {
    }
}

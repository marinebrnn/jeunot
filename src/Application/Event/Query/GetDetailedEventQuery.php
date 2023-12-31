<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\QueryInterface;

final class GetDetailedEventQuery implements QueryInterface
{
    public function __construct(
        public readonly string $uuid,
        public ?string $loggedUserUuid = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Event\Repository;

use App\Domain\Event\Event;

interface EventRepositoryInterface
{
    public function findActiveEvents(int $pageSize, int $page, ?string $loggedUserUuid): array;

    public function findDetailedEvent(string $uuid, ?string $loggedUserUuid): array;

    public function findOneByUuid(string $uuid): ?Event;
}

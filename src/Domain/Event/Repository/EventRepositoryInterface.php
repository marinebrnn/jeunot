<?php

declare(strict_types=1);

namespace App\Domain\Event\Repository;

interface EventRepositoryInterface
{
    public function findActiveEvents(int $pageSize, int $page): array;

    public function findOneByUuid(string $uuid): array;
}

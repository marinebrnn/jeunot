<?php

declare(strict_types=1);

namespace App\Domain\Post\Repository;

interface PostRepositoryInterface
{
    public function findPublishedPosts(int $pageSize, int $page): array;

    public function findDetailedPost(string $slug): array;
}

<?php

declare(strict_types=1);

namespace App\Application\Post\Query;

use App\Domain\Pagination;
use App\Domain\Post\Repository\PostRepositoryInterface;

final class GetPublishedPostsQueryHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {
    }

    public function __invoke(GetPublishedPostsQuery $query): Pagination
    {
        ['posts' => $posts, 'count' => $count] = $this->postRepository->findPublishedPosts(
            $query->pageSize,
            $query->page,
        );

        return new Pagination($posts, $count, $query->page, $query->pageSize);
    }
}

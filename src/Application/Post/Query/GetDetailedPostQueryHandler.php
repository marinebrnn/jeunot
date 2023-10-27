<?php

declare(strict_types=1);

namespace App\Application\Post\Query;

use App\Application\Post\View\DetailedPostView;
use App\Domain\Post\Exception\PostNotFoundException;
use App\Domain\Post\Repository\PostRepositoryInterface;

final class GetDetailedPostQueryHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
    ) {
    }

    public function __invoke(GetDetailedPostQuery $query): DetailedPostView
    {
        $post = $this->postRepository->findDetailedPost($query->slug);
        if (!$post) {
            throw new PostNotFoundException();
        }

        return current($post);
    }
}

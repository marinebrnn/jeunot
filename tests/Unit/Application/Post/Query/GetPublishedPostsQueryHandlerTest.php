<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Post\Query;

use App\Application\Post\Query\GetPublishedPostsQuery;
use App\Application\Post\Query\GetPublishedPostsQueryHandler;
use App\Application\Post\View\SummarizedPostView;
use App\Domain\Pagination;
use App\Domain\Post\Repository\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetPublishedPostsQueryHandlerTest extends TestCase
{
    public function testGetPosts(): void
    {
        $publicationDate = new \DateTime('2023-09-13 09:00:00');

        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $postRepository
            ->expects(self::once())
            ->method('findPublishedPosts')
            ->with(20, 1)
            ->willReturn([
                'count' => 1,
                'posts' => [
                    new SummarizedPostView(
                        'creation-de-jeunot',
                        'Création de jeunot',
                        $publicationDate,
                        null,
                    ),
                ],
            ]);

        $query = new GetPublishedPostsQuery(1, 20);
        $handler = new GetPublishedPostsQueryHandler($postRepository);

        $this->assertEquals(
            new Pagination(
                [
                    new SummarizedPostView(
                        'creation-de-jeunot',
                        'Création de jeunot',
                        $publicationDate,
                        null,
                    ),
                ],
                1,
                1,
                20,
            ),
            ($handler)($query),
        );
    }

    public function testEmptyList(): void
    {
        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $postRepository
            ->expects(self::once())
            ->method('findPublishedPosts')
            ->with(20, 1)
            ->willReturn([
                'count' => 0,
                'posts' => [],
            ]);

        $query = new GetPublishedPostsQuery(1, 20);
        $handler = new GetPublishedPostsQueryHandler($postRepository);

        $this->assertEquals(
            new Pagination([], 0, 1, 20),
            ($handler)($query),
        );
    }
}

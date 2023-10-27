<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Post\Query;

use App\Application\Post\Query\GetDetailedPostQuery;
use App\Application\Post\Query\GetDetailedPostQueryHandler;
use App\Application\Post\View\DetailedPostView;
use App\Domain\Post\Exception\PostNotFoundException;
use App\Domain\Post\Repository\PostRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetDetailedPostQueryHandlerTest extends TestCase
{
    public function testGetPost(): void
    {
        $publicationDate = new \DateTime('2023-09-13 09:00:00');

        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $postRepository
            ->expects(self::once())
            ->method('findDetailedPost')
            ->with('creation-de-jeunot')
            ->willReturn([
                new DetailedPostView(
                    title: 'Création de jeunot',
                    slug: 'creation-de-jeunot',
                    description: 'Description',
                    picture: null,
                    publicationDate: $publicationDate,
                ),
            ]);

        $query = new GetDetailedPostQuery('creation-de-jeunot');
        $handler = new GetDetailedPostQueryHandler($postRepository);

        $this->assertEquals(
            new DetailedPostView(
                title: 'Création de jeunot',
                slug: 'creation-de-jeunot',
                description: 'Description',
                picture: null,
                publicationDate: $publicationDate,
            ),
            ($handler)($query),
        );
    }

    public function testNotFound(): void
    {
        $this->expectException(PostNotFoundException::class);

        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $postRepository
            ->expects(self::once())
            ->method('findDetailedPost')
            ->with('creation-de-jeunot')
            ->willReturn([]);

        $query = new GetDetailedPostQuery('creation-de-jeunot');
        $handler = new GetDetailedPostQueryHandler($postRepository);

        ($handler)($query);
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Post;

use App\Application\Post\View\DetailedPostView;
use App\Application\Post\View\SummarizedPostView;
use App\Domain\Post\Post;
use App\Domain\Post\Repository\PostRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

final class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Post::class);
    }

    public function findPublishedPosts(int $pageSize, int $page): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select(sprintf(
                'NEW %s(
                    p.slug,
                    p.title,
                    p.publicationDate,
                    p.picture
                )',
                SummarizedPostView::class,
            ))
            ->where('p.published = true')
            ->orderBy('p.publicationDate', 'DESC')
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize);

        $query = $qb->getQuery();
        $paginator = new Paginator($query, false);
        $paginator->setUseOutputWalkers(false);

        $result = [
            'posts' => [],
            'count' => $paginator->count(),
        ];

        foreach ($paginator as $event) {
            array_push($result['posts'], $event);
        }

        return $result;
    }

    public function findDetailedPost(string $slug): array
    {
        return $this->createQueryBuilder('p')
            ->select(sprintf(
                'NEW %s(
                    p.slug,
                    p.title,
                    p.description,
                    p.publicationDate,
                    p.picture
                )',
                DetailedPostView::class,
            ))
            ->where('p.published = true')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}

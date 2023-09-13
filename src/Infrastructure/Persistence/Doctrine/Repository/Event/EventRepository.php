<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Event;

use App\Domain\Event\Event;
use App\Domain\Event\Repository\EventRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

final class EventRepository extends ServiceEntityRepository implements EventRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findActiveEvents(int $pageSize, int $page): array
    {
        $query = $this->createQueryBuilder('e')
            ->select('e.uuid, e.title, e.location, e.picture, e.startDate')
            ->addSelect('
                (
                    SELECT count(a.uuid)
                    FROM App\Domain\Event\Attendee a
                    WHERE a.event = e.uuid
                ) AS nbAttendees
            ')
            ->where('e.published = true')
            ->orderBy('e.startDate', 'DESC')
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize)
            ->getQuery();

        $paginator = new Paginator($query, false);
        $paginator->setUseOutputWalkers(false);

        $result = [
            'events' => [],
            'count' => $paginator->count(),
        ];

        foreach ($paginator as $event) {
            array_push($result['events'], $event);
        }

        return $result;
    }

    public function findOneByUuid(string $uuid): array
    {
        return $this->createQueryBuilder('e')
            ->select('
                e.uuid,
                e.title,
                e.location,
                e.picture,
                e.startDate,
                e.endDate,
                e.description,
                e.initialAvailablePlaces,
                o.firstName as ownerFirstName
            ')
            ->addSelect('
                (
                    SELECT count(a.uuid)
                    FROM App\Domain\Event\Attendee a
                    WHERE a.event = e.uuid
                ) AS nbAttendees
            ')
            ->innerJoin('e.owner', 'o')
            ->where('e.published = true')
            ->andWhere('e.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}

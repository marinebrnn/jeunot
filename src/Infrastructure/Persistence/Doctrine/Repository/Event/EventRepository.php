<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Event;

use App\Application\DateUtilsInterface;
use App\Domain\Event\Event;
use App\Domain\Event\Repository\EventRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

final class EventRepository extends ServiceEntityRepository implements EventRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly DateUtilsInterface $dateUtils,
    ) {
        parent::__construct($registry, Event::class);
    }

    private const NB_ATTENDEE_QUERY = '
        SELECT count(DISTINCT a1.uuid)
        FROM App\Domain\Event\Attendee a1
        WHERE a1.event = e.uuid';

    private const IS_LOGGED_USER_REGISTERED_FOR_EVENT_QUERY = '
        SELECT count(DISTINCT a2.uuid)
        FROM App\Domain\Event\Attendee a2
        WHERE a2.event = e.uuid
        AND a2.user = :userUuid';

    public function findActiveEvents(
        int $pageSize,
        int $page,
        ?string $loggedUserUuid,
        bool $displayOnlyLoggedUserEvents = false,
    ): array {
        $qb = $this->createQueryBuilder('e')
            ->select('e.uuid, e.title, e.location, e.picture, e.startDate')
            ->addSelect(sprintf('(%s) as nbAttendees', self::NB_ATTENDEE_QUERY))
            ->where('e.published = true')
            ->andWhere('e.endDate > :currentDate')
            ->setParameter('currentDate', $this->dateUtils->getNow())
            ->orderBy('e.startDate', 'DESC')
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize);

        if ($loggedUserUuid) {
            if ($displayOnlyLoggedUserEvents) {
                $qb->andWhere(sprintf('(%s) > 0', self::IS_LOGGED_USER_REGISTERED_FOR_EVENT_QUERY));
            } else {
                $qb->addSelect(sprintf('(%s) as isLoggedUserRegisteredForEvent', self::IS_LOGGED_USER_REGISTERED_FOR_EVENT_QUERY));
            }

            $qb->setParameter('userUuid', $loggedUserUuid);
        }

        $query = $qb->getQuery();
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

    public function findDetailedEvent(string $uuid, ?string $loggedUserUuid): array
    {
        $qb = $this->createQueryBuilder('e')
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
            ->addSelect(sprintf('(%s) as nbAttendees', self::NB_ATTENDEE_QUERY))
            ->innerJoin('e.owner', 'o')
            ->where('e.published = true')
            ->andWhere('e.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->setMaxResults(1);

        if ($loggedUserUuid) {
            $qb
                ->addSelect(sprintf('(%s) as isLoggedUserRegisteredForEvent', self::IS_LOGGED_USER_REGISTERED_FOR_EVENT_QUERY))
                ->setParameter('userUuid', $loggedUserUuid);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneByUuid(string $uuid): ?Event
    {
        return $this->createQueryBuilder('e')
            ->addSelect('o')
            ->where('e.published = true')
            ->andWhere('e.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->innerJoin('e.owner', 'o')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

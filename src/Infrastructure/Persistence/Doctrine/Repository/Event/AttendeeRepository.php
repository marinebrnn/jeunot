<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository\Event;

use App\Domain\Event\Attendee;
use App\Domain\Event\Event;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class AttendeeRepository extends ServiceEntityRepository implements AttendeeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendee::class);
    }

    public function add(Attendee $attendee): void
    {
        $this->getEntityManager()->persist($attendee);
    }

    public function countByEvent(Event $event): int
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.uuid)')
            ->where('a.event = :event')
            ->setParameters([
                'event' => $event,
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByEventAndUser(Event $event, User $user): int
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.uuid)')
            ->where('a.event = :event')
            ->andWhere('a.user = :user')
            ->setParameters([
                'user' => $user,
                'event' => $event,
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findOneByEventAndUser(Event $event, User $user): ?Attendee
    {
        return $this->createQueryBuilder('a')
            ->where('a.event = :event')
            ->andWhere('a.user = :user')
            ->setParameters([
                'user' => $user,
                'event' => $event,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAttendeesByEvent(Event $event): array
    {
        return $this->createQueryBuilder('a')
            ->select('u.firstName', 'u.lastName')
            ->where('a.event =  :event')
            ->innerJoin('a.user', 'u')
            ->setParameters([
                'event' => $event,
            ])
            ->getQuery()
            ->getResult();
    }

    public function delete(Attendee $attendee): void
    {
        $this->getEntityManager()->remove($attendee);
    }
}

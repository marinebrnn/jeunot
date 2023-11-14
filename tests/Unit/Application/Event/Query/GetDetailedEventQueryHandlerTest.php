<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Query;

use App\Application\DateUtilsInterface;
use App\Application\Event\Query\GetDetailedEventQuery;
use App\Application\Event\Query\GetDetailedEventQueryHandler;
use App\Application\Event\View\DetailedEventView;
use App\Application\Event\View\OwnerView;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\EventRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetDetailedEventQueryHandlerTest extends TestCase
{
    private function loadFixtures(): array
    {
        $startDate = new \DateTime('2023-09-13 19:00:00');
        $endDate = new \DateTime('2023-09-13 21:00:00');

        return [
            [
                'uuid' => 'a8597889-a063-4da9-b536-2aef6988c993',
                'title' => 'Soirée dansante',
                'description' => 'Lorem ipsum',
                'location' => 'Paris',
                'picture' => null,
                'nbAttendees' => 55,
                'initialAvailablePlaces' => 100,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'ownerUuid' => '0656759e-2f59-7a6b-8000-6877905a5dad',
                'ownerFirstName' => 'Mathieu',
                'ownerBirthday' => \DateTimeImmutable::createFromFormat('Y-m-d', '1958-01-01'),
                'ownerDisplayMyAge' => true,
                'ownerCity' => 'Saint-Ouen',
                'ownerAvatar' => null,
                'isLoggedUserRegisteredForEvent' => 0,
                'isLoggedUserRegisteredForEventShouldBe' => false,
            ],
            [
                'uuid' => 'a8597889-a063-4da9-b536-2aef6988c993',
                'title' => 'Soirée dansante',
                'description' => 'Lorem ipsum',
                'location' => 'Paris',
                'picture' => null,
                'nbAttendees' => 55,
                'initialAvailablePlaces' => 100,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'ownerUuid' => '0656759e-2f59-7a6b-8000-6877905a5dad',
                'ownerFirstName' => 'Mathieu',
                'ownerBirthday' => \DateTimeImmutable::createFromFormat('Y-m-d', '1958-01-01'),
                'ownerDisplayMyAge' => true,
                'ownerCity' => 'Saint-Ouen',
                'ownerAvatar' => null,
                'isLoggedUserRegisteredForEvent' => 1,
                'isLoggedUserRegisteredForEventShouldBe' => true,
            ],
            [
                'uuid' => 'a8597889-a063-4da9-b536-2aef6988c993',
                'title' => 'Soirée dansante',
                'description' => 'Lorem ipsum',
                'location' => 'Paris',
                'picture' => null,
                'nbAttendees' => 55,
                'initialAvailablePlaces' => 100,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'ownerUuid' => '0656759e-2f59-7a6b-8000-6877905a5dad',
                'ownerFirstName' => 'Mathieu',
                'ownerBirthday' => \DateTimeImmutable::createFromFormat('Y-m-d', '1958-01-01'),
                'ownerDisplayMyAge' => true,
                'ownerCity' => 'Saint-Ouen',
                'ownerAvatar' => null,
                'isLoggedUserRegisteredForEventShouldBe' => false,
            ],
        ];
    }

    /**
     * @dataProvider loadFixtures
     */
    public function testGetEvent(...$event): void
    {
        $startDate = new \DateTime('2023-09-13 19:00:00');
        $endDate = new \DateTime('2023-09-13 21:00:00');

        $eventRepository = $this->createMock(EventRepositoryInterface::class);
        $eventRepository
            ->expects(self::once())
            ->method('findDetailedEvent')
            ->with('a8597889-a063-4da9-b536-2aef6988c993')
            ->willReturn([
                [
                    'uuid' => $event[0],
                    'title' => $event[1],
                    'description' => $event[2],
                    'location' => $event[3],
                    'picture' => $event[4],
                    'nbAttendees' => $event[5],
                    'initialAvailablePlaces' => $event[6],
                    'startDate' => $event[7],
                    'endDate' => $event[8],
                    'ownerUuid' => $event[9],
                    'ownerFirstName' => $event[10],
                    'ownerBirthday' => $event[11],
                    'ownerDisplayMyAge' => $event[12],
                    'ownerCity' => $event[13],
                    'ownerAvatar' => $event[14],
                    'isLoggedUserRegisteredForEvent' => $event[15],
                ],
            ]);

        $dateUtils = $this->createMock(DateUtilsInterface::class);

        $dateUtils->expects(self::once())
            ->method('getNow')
            ->willReturn(\DateTimeImmutable::createFromFormat('Y-m-d', '2023-01-12'));

        $dateUtils->expects(self::once())
            ->method('getDaysInterval')
            ->with($event[7], $event[8])
            ->willReturn(0);

        $query = new GetDetailedEventQuery('a8597889-a063-4da9-b536-2aef6988c993');
        $handler = new GetDetailedEventQueryHandler($eventRepository, $dateUtils);

        $this->assertEquals(
            new DetailedEventView(
                uuid: 'a8597889-a063-4da9-b536-2aef6988c993',
                title: 'Soirée dansante',
                description: 'Lorem ipsum',
                location: 'Paris',
                picture: null,
                nbAttendees: 55,
                nbAvailablePlaces: 45,
                startDate: $startDate,
                endDate: $endDate,
                owner: new OwnerView(
                    uuid: '0656759e-2f59-7a6b-8000-6877905a5dad',
                    firstName: 'Mathieu',
                    age: 65,
                    city: 'Saint-Ouen',
                    avatar: null,
                ),
                isLoggedUserRegisteredForEvent: end($event),
                isOverSeveralDays: false,
            ),
            ($handler)($query),
        );
    }

    public function testEventNotFound(): void
    {
        $this->expectException(EventNotFoundException::class);

        $eventRepository = $this->createMock(EventRepositoryInterface::class);
        $eventRepository
            ->expects(self::once())
            ->method('findDetailedEvent')
            ->with('a8597889-a063-4da9-b536-2aef6988c993')
            ->willReturn([]);

        $dateUtils = $this->createMock(DateUtilsInterface::class);

        $query = new GetDetailedEventQuery('a8597889-a063-4da9-b536-2aef6988c993');
        $handler = new GetDetailedEventQueryHandler($eventRepository, $dateUtils);
        ($handler)($query);
    }
}

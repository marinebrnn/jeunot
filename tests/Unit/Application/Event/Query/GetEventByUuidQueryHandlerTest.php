<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Query;

use App\Application\Event\Query\GetEventByUuidQuery;
use App\Application\Event\Query\GetEventByUuidQueryHandler;
use App\Application\Event\View\EventDetailView;
use App\Application\Event\View\OwnerView;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\EventRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class GetEventByUuidQueryHandlerTest extends TestCase
{
    public function testGetEvent(): void
    {
        $startDate = new \DateTime('2023-09-13 19:00:00');
        $endDate = new \DateTime('2023-09-13 21:00:00');

        $eventRepository = $this->createMock(EventRepositoryInterface::class);
        $eventRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('a8597889-a063-4da9-b536-2aef6988c993')
            ->willReturn([
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
                    'ownerFirstName' => 'Mathieu',
                ],
            ]);

        $query = new GetEventByUuidQuery('a8597889-a063-4da9-b536-2aef6988c993');
        $handler = new GetEventByUuidQueryHandler($eventRepository);

        $this->assertEquals(
            new EventDetailView(
                uuid: 'a8597889-a063-4da9-b536-2aef6988c993',
                title: 'Soirée dansante',
                description: 'Lorem ipsum',
                location: 'Paris',
                picture: null,
                nbAttendees: 55,
                nbAvailablePlaces: 45,
                startDate: $startDate,
                endDate: $endDate,
                owner: new OwnerView('Mathieu'),
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
            ->method('findOneByUuid')
            ->with('a8597889-a063-4da9-b536-2aef6988c993')
            ->willReturn([]);

        $query = new GetEventByUuidQuery('a8597889-a063-4da9-b536-2aef6988c993');
        $handler = new GetEventByUuidQueryHandler($eventRepository);
        ($handler)($query);
    }
}

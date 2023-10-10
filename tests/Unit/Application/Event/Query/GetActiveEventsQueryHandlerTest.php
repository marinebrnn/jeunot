<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Query;

use App\Application\Event\Query\GetActiveEventsQuery;
use App\Application\Event\Query\GetActiveEventsQueryHandler;
use App\Application\Event\View\SummarizedEventView;
use App\Domain\Event\Repository\EventRepositoryInterface;
use App\Domain\Pagination;
use PHPUnit\Framework\TestCase;

final class GetActiveEventsQueryHandlerTest extends TestCase
{
    public function testGetEvents(): void
    {
        $startDate = new \DateTime('2023-09-13 09:00:00');

        $eventRepository = $this->createMock(EventRepositoryInterface::class);
        $eventRepository
            ->expects(self::once())
            ->method('findActiveEvents')
            ->with(20, 1, null, false)
            ->willReturn([
                'count' => 3,
                'events' => [
                    [
                        'uuid' => '018a8e0b-ad0a-711d-becc-f963913de524',
                        'title' => 'Balade et pique-nique en forêt de Chevreuse',
                        'location' => 'Saint Remy les Chevreuses',
                        'picture' => null,
                        'nbAttendees' => 0,
                        'startDate' => $startDate,
                        'isLoggedUserRegisteredForEvent' => true,
                    ],
                    [
                        'uuid' => '0f15c8c3-dbb5-4817-b01e-417f1cf31025',
                        'title' => 'Concert',
                        'location' => 'Montrouge',
                        'picture' => null,
                        'nbAttendees' => 0,
                        'startDate' => $startDate,
                        'isLoggedUserRegisteredForEvent' => false,
                    ],
                    [
                        'uuid' => 'f03d165f-d9bd-4b1d-acf2-3fa275622449',
                        'title' => 'Spectacle de rapaces',
                        'location' => 'Provins',
                        'picture' => null,
                        'nbAttendees' => 0,
                        'startDate' => $startDate,
                    ],
                ],
            ]);

        $query = new GetActiveEventsQuery(1, 20);
        $handler = new GetActiveEventsQueryHandler($eventRepository);

        $this->assertEquals(
            new Pagination(
                [
                    new SummarizedEventView(
                        uuid: '018a8e0b-ad0a-711d-becc-f963913de524',
                        title: 'Balade et pique-nique en forêt de Chevreuse',
                        location: 'Saint Remy les Chevreuses',
                        picture: null,
                        nbAttendees: 0,
                        startDate: $startDate,
                        isLoggedUserRegisteredForEvent: true,
                    ),
                    new SummarizedEventView(
                        uuid: '0f15c8c3-dbb5-4817-b01e-417f1cf31025',
                        title: 'Concert',
                        location: 'Montrouge',
                        picture: null,
                        nbAttendees: 0,
                        startDate: $startDate,
                        isLoggedUserRegisteredForEvent: false,
                    ),
                    new SummarizedEventView(
                        uuid: 'f03d165f-d9bd-4b1d-acf2-3fa275622449',
                        title: 'Spectacle de rapaces',
                        location: 'Provins',
                        picture: null,
                        nbAttendees: 0,
                        startDate: $startDate,
                        isLoggedUserRegisteredForEvent: false,
                    ),
                ],
                3,
                1,
                20,
            ),
            ($handler)($query),
        );
    }

    public function testLoggedUserEvents(): void
    {
        $startDate = new \DateTime('2023-09-13 09:00:00');

        $eventRepository = $this->createMock(EventRepositoryInterface::class);
        $eventRepository
            ->expects(self::once())
            ->method('findActiveEvents')
            ->with(6, 1, '475d9b57-af6b-4e9e-892c-00133591c7db', true)
            ->willReturn([
                'count' => 1,
                'events' => [
                    [
                        'uuid' => '018a8e0b-ad0a-711d-becc-f963913de524',
                        'title' => 'Balade et pique-nique en forêt de Chevreuse',
                        'location' => 'Saint Remy les Chevreuses',
                        'picture' => null,
                        'nbAttendees' => 0,
                        'startDate' => $startDate,
                    ],
                ],
            ]);

        $query = new GetActiveEventsQuery(1, 6, '475d9b57-af6b-4e9e-892c-00133591c7db', true);
        $handler = new GetActiveEventsQueryHandler($eventRepository);

        $this->assertEquals(
            new Pagination(
                [
                    new SummarizedEventView(
                        uuid: '018a8e0b-ad0a-711d-becc-f963913de524',
                        title: 'Balade et pique-nique en forêt de Chevreuse',
                        location: 'Saint Remy les Chevreuses',
                        picture: null,
                        nbAttendees: 0,
                        startDate: $startDate,
                        isLoggedUserRegisteredForEvent: true,
                    ),
                ],
                1,
                1,
                6,
            ),
            ($handler)($query),
        );
    }

    public function testEmptyList(): void
    {
        $eventRepository = $this->createMock(EventRepositoryInterface::class);
        $eventRepository
            ->expects(self::once())
            ->method('findActiveEvents')
            ->with(20, 1, null, false)
            ->willReturn([
                'count' => 0,
                'events' => [],
            ]);

        $query = new GetActiveEventsQuery(1, 20);
        $handler = new GetActiveEventsQueryHandler($eventRepository);

        $this->assertEquals(
            new Pagination([], 0, 1, 20),
            ($handler)($query),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Query;

use App\Application\Event\Query\GetActiveEventsQuery;
use App\Application\Event\Query\GetActiveEventsQueryHandler;
use App\Application\Event\View\EventSummaryView;
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

        $query = new GetActiveEventsQuery(1, 20);
        $handler = new GetActiveEventsQueryHandler($eventRepository);

        $this->assertEquals(
            new Pagination(
                [
                    new EventSummaryView(
                        uuid: '018a8e0b-ad0a-711d-becc-f963913de524',
                        title: 'Balade et pique-nique en forêt de Chevreuse',
                        location: 'Saint Remy les Chevreuses',
                        picture: null,
                        nbAttendees: 0,
                        startDate: $startDate,
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
        $eventRepository = $this->createMock(EventRepositoryInterface::class);
        $eventRepository
            ->expects(self::once())
            ->method('findActiveEvents')
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

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Command;

use App\Application\Event\Command\UnregisterUserForAnEventCommand;
use App\Application\Event\Command\UnregisterUserForAnEventCommandHandler;
use App\Domain\Event\Attendee;
use App\Domain\Event\Event;
use App\Domain\Event\Exception\AttendeeNotFoundException;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\Event\Repository\EventRepositoryInterface;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UnregisterUserForAnEventCommandHandlerTest extends TestCase
{
    private MockObject $eventRepository;
    private MockObject $attendeeRepository;
    private MockObject $user;
    private MockObject $event;

    public function setUp(): void
    {
        $this->eventRepository = $this->createMock(EventRepositoryInterface::class);
        $this->attendeeRepository = $this->createMock(AttendeeRepositoryInterface::class);
        $this->user = $this->createMock(User::class);
        $this->event = $this->createMock(Event::class);
    }

    public function testEventNotFound(): void
    {
        $this->expectException(EventNotFoundException::class);

        $this->eventRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8')
            ->willReturn(null);

        $this->attendeeRepository
            ->expects(self::never())
            ->method('findOneByEventAndUser');

        $this->attendeeRepository
            ->expects(self::never())
            ->method('delete');

        $handler = new UnregisterUserForAnEventCommandHandler(
            $this->eventRepository,
            $this->attendeeRepository,
        );
        ($handler)(new UnregisterUserForAnEventCommand('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8', $this->user));
    }

    public function testAttendeeNotFound(): void
    {
        $this->expectException(AttendeeNotFoundException::class);

        $this->eventRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8')
            ->willReturn($this->event);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('findOneByEventAndUser')
            ->with($this->event, $this->user)
            ->willReturn(null);

        $this->attendeeRepository
            ->expects(self::never())
            ->method('delete');

        $handler = new UnregisterUserForAnEventCommandHandler(
            $this->eventRepository,
            $this->attendeeRepository,
        );
        ($handler)(new UnregisterUserForAnEventCommand('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8', $this->user));
    }

    public function testUnregister(): void
    {
        $attendee = $this->createMock(Attendee::class);

        $this->eventRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8')
            ->willReturn($this->event);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('findOneByEventAndUser')
            ->with($this->event, $this->user)
            ->willReturn($attendee);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('delete')
            ->with($attendee);

        $handler = new UnregisterUserForAnEventCommandHandler(
            $this->eventRepository,
            $this->attendeeRepository,
        );
        ($handler)(new UnregisterUserForAnEventCommand('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8', $this->user));
    }
}

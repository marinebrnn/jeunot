<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event\Specification;

use App\Domain\Event\Event;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\Event\Specification\CanUserRegisterToEvent;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CanUserRegisterToEventTest extends TestCase
{
    private MockObject $attendeeRepository;
    private MockObject $user;
    private MockObject $event;

    public function setUp(): void
    {
        $this->attendeeRepository = $this->createMock(AttendeeRepositoryInterface::class);
        $this->event = $this->createMock(Event::class);
        $this->user = $this->createMock(User::class);
    }

    public function testNoMoreAvailablePlaces(): void
    {
        $this->event
            ->expects(self::once())
            ->method('getInitialAvailablePlaces')
            ->willReturn(5);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('countByEvent')
            ->with($this->event)
            ->willReturn(10);

        $this->attendeeRepository
            ->expects(self::never())
            ->method('countByEventAndUser');

        $pattern = new CanUserRegisterToEvent($this->attendeeRepository);
        $this->assertFalse($pattern->isSatisfiedBy($this->event, $this->user));
    }

    public function testUserAlreadyRegistered(): void
    {
        $this->event
            ->expects(self::once())
            ->method('getInitialAvailablePlaces')
            ->willReturn(5);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('countByEvent')
            ->with($this->event)
            ->willReturn(2);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('countByEventAndUser')
            ->with($this->event, $this->user)
            ->willReturn(1);

        $pattern = new CanUserRegisterToEvent($this->attendeeRepository);
        $this->assertFalse($pattern->isSatisfiedBy($this->event, $this->user));
    }

    public function testCanRegistered(): void
    {
        $this->event
            ->expects(self::once())
            ->method('getInitialAvailablePlaces')
            ->willReturn(5);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('countByEvent')
            ->with($this->event)
            ->willReturn(2);

        $this->attendeeRepository
            ->expects(self::once())
            ->method('countByEventAndUser')
            ->with($this->event, $this->user)
            ->willReturn(0);

        $pattern = new CanUserRegisterToEvent($this->attendeeRepository);
        $this->assertTrue($pattern->isSatisfiedBy($this->event, $this->user));
    }
}

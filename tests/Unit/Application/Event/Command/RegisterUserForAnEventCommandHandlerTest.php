<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Command;

use App\Application\CommandBusInterface;
use App\Application\DateUtilsInterface;
use App\Application\Event\Command\Mail\SendCommentToEventOwnerMailCommand;
use App\Application\Event\Command\Mail\SendUserRegistrationConfirmationMailCommand;
use App\Application\Event\Command\RegisterUserForAnEventCommand;
use App\Application\Event\Command\RegisterUserForAnEventCommandHandler;
use App\Application\IdFactoryInterface;
use App\Domain\Event\Attendee;
use App\Domain\Event\Event;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Exception\UserCannotRegisterToEventException;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\Event\Repository\EventRepositoryInterface;
use App\Domain\Event\Specification\CanUserRegisterToEvent;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RegisterUserForAnEventCommandHandlerTest extends TestCase
{
    private MockObject $eventRepository;
    private MockObject $attendeeRepository;
    private MockObject $idFactory;
    private MockObject $dateUtils;
    private MockObject $commandBus;
    private MockObject $canUserRegisterToEvent;
    private MockObject $user;
    private MockObject $event;

    public function setUp(): void
    {
        $this->eventRepository = $this->createMock(EventRepositoryInterface::class);
        $this->attendeeRepository = $this->createMock(AttendeeRepositoryInterface::class);
        $this->idFactory = $this->createMock(IdFactoryInterface::class);
        $this->dateUtils = $this->createMock(DateUtilsInterface::class);
        $this->commandBus = $this->createMock(CommandBusInterface::class);
        $this->canUserRegisterToEvent = $this->createMock(CanUserRegisterToEvent::class);
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

        $this->canUserRegisterToEvent
            ->expects(self::never())
            ->method('isSatisfiedBy');

        $this->attendeeRepository
            ->expects(self::never())
            ->method('add');

        $this->attendeeRepository
            ->expects(self::never())
            ->method('add');

        $this->commandBus
            ->expects(self::never())
            ->method('dispatchAsync');

        $this->idFactory
            ->expects(self::never())
            ->method('make');

        $this->dateUtils
            ->expects(self::never())
            ->method('getNow');

        $handler = new RegisterUserForAnEventCommandHandler(
            $this->eventRepository,
            $this->attendeeRepository,
            $this->idFactory,
            $this->dateUtils,
            $this->commandBus,
            $this->canUserRegisterToEvent,
        );
        ($handler)(new RegisterUserForAnEventCommand('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8', $this->user));
    }

    public function testUserCannotRegister(): void
    {
        $this->expectException(UserCannotRegisterToEventException::class);

        $this->eventRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8')
            ->willReturn($this->event);

        $this->canUserRegisterToEvent
            ->expects(self::once())
            ->method('isSatisfiedBy')
            ->with($this->event, $this->user)
            ->willReturn(false);

        $this->attendeeRepository
            ->expects(self::never())
            ->method('add');

        $this->attendeeRepository
            ->expects(self::never())
            ->method('add');

        $this->commandBus
            ->expects(self::never())
            ->method('dispatchAsync');

        $this->idFactory
            ->expects(self::never())
            ->method('make');

        $this->dateUtils
            ->expects(self::never())
            ->method('getNow');

        $handler = new RegisterUserForAnEventCommandHandler(
            $this->eventRepository,
            $this->attendeeRepository,
            $this->idFactory,
            $this->dateUtils,
            $this->commandBus,
            $this->canUserRegisterToEvent,
        );
        ($handler)(new RegisterUserForAnEventCommand('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8', $this->user));
    }

    public function testRegister(): void
    {
        $registeredOn = new \DateTimeImmutable('2023-09-19');

        $this->user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');

        $this->eventRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8')
            ->willReturn($this->event);

        $this->canUserRegisterToEvent
            ->expects(self::once())
            ->method('isSatisfiedBy')
            ->with($this->event, $this->user)
            ->willReturn(true);

        $this->idFactory
            ->expects(self::once())
            ->method('make')
            ->willReturn('575c954e-e53f-44a2-8ed8-bd19e985e2a6');

        $this->dateUtils
            ->expects(self::once())
            ->method('getNow')
            ->willReturn($registeredOn);

        $this->commandBus
            ->expects(self::once())
            ->method('dispatchAsync')
            ->with(
                $this->equalTo(
                    new SendUserRegistrationConfirmationMailCommand(
                        email: 'mathieu@fairness.coop',
                        event: $this->event,
                    ),
                ),
            );

        $this->attendeeRepository
            ->expects(self::once())
            ->method('add')
            ->with(
                $this->equalTo(
                    new Attendee(
                        uuid: '575c954e-e53f-44a2-8ed8-bd19e985e2a6',
                        user: $this->user,
                        event: $this->event,
                        registeredOn: $registeredOn,
                    ),
                ),
            );

        $handler = new RegisterUserForAnEventCommandHandler(
            $this->eventRepository,
            $this->attendeeRepository,
            $this->idFactory,
            $this->dateUtils,
            $this->commandBus,
            $this->canUserRegisterToEvent,
        );
        ($handler)(new RegisterUserForAnEventCommand('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8', $this->user));
    }

    public function testRegisterWithComment(): void
    {
        $registeredOn = new \DateTimeImmutable('2023-09-19');

        $this->user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');

        $this->eventRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8')
            ->willReturn($this->event);

        $this->canUserRegisterToEvent
            ->expects(self::once())
            ->method('isSatisfiedBy')
            ->with($this->event, $this->user)
            ->willReturn(true);

        $this->idFactory
            ->expects(self::once())
            ->method('make')
            ->willReturn('575c954e-e53f-44a2-8ed8-bd19e985e2a6');

        $this->dateUtils
            ->expects(self::once())
            ->method('getNow')
            ->willReturn($registeredOn);

        $cmd = new SendUserRegistrationConfirmationMailCommand('mathieu@fairness.coop', $this->event);
        $cmd2 = new SendCommentToEventOwnerMailCommand($this->event, $this->user, 'Ceci est un commentaire');

        $matcher = self::exactly(2);
        $this->commandBus
            ->expects($matcher)
            ->method('dispatchAsync')
            ->willReturnCallback(
                fn ($command) => match ($matcher->getInvocationCount()) {
                    1 => $this->assertEquals($cmd, $command),
                    2 => $this->assertEquals($cmd2, $command),
                },
            );

        $this->attendeeRepository
            ->expects(self::once())
            ->method('add')
            ->with(
                $this->equalTo(
                    new Attendee(
                        uuid: '575c954e-e53f-44a2-8ed8-bd19e985e2a6',
                        user: $this->user,
                        event: $this->event,
                        registeredOn: $registeredOn,
                    ),
                ),
            );

        $handler = new RegisterUserForAnEventCommandHandler(
            $this->eventRepository,
            $this->attendeeRepository,
            $this->idFactory,
            $this->dateUtils,
            $this->commandBus,
            $this->canUserRegisterToEvent,
        );
        $command = new RegisterUserForAnEventCommand('6cfa676e-7aa8-45a2-bee4-27b0280c8ca8', $this->user);
        $command->comment = 'Ceci est un commentaire';
        ($handler)($command);
    }
}

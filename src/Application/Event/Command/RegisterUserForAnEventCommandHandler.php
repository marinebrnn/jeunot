<?php

declare(strict_types=1);

namespace App\Application\Event\Command;

use App\Application\CommandBusInterface;
use App\Application\DateUtilsInterface;
use App\Application\Event\Command\Mail\SendCommentToEventOwnerMailCommand;
use App\Application\Event\Command\Mail\SendUserRegistrationConfirmationMailCommand;
use App\Application\IdFactoryInterface;
use App\Domain\Event\Attendee;
use App\Domain\Event\Event;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Exception\UserCannotRegisterToEventException;
use App\Domain\Event\Repository\AttendeeRepositoryInterface;
use App\Domain\Event\Repository\EventRepositoryInterface;
use App\Domain\Event\Specification\CanUserRegisterToEvent;

final readonly class RegisterUserForAnEventCommandHandler
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private AttendeeRepositoryInterface $attendeeRepository,
        private IdFactoryInterface $idFactory,
        private DateUtilsInterface $dateUtils,
        private CommandBusInterface $commandBus,
        private CanUserRegisterToEvent $canUserRegisterToEvent,
    ) {
    }

    public function __invoke(RegisterUserForAnEventCommand $command): void
    {
        $event = $this->eventRepository->findOneByUuid($command->eventUuid);
        if (!$event instanceof Event) {
            throw new EventNotFoundException();
        }

        if (false === $this->canUserRegisterToEvent->isSatisfiedBy($event, $command->user)) {
            throw new UserCannotRegisterToEventException();
        }

        $this->attendeeRepository->add(
            new Attendee(
                uuid: $this->idFactory->make(),
                user: $command->user,
                event: $event,
                registeredOn: $this->dateUtils->getNow(),
            ),
        );

        $this->commandBus->dispatchAsync(
            new SendUserRegistrationConfirmationMailCommand(
                email: $command->user->getEmail(),
                event: $event,
            ),
        );

        if ($command->comment) {
            $this->commandBus->dispatchAsync(
                new SendCommentToEventOwnerMailCommand(
                    event: $event,
                    attendee: $command->user,
                    comment: $command->comment,
                ),
            );
        }
    }
}

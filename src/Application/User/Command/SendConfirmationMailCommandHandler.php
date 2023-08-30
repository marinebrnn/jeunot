<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\CommandBusInterface;
use App\Application\Mail\User\ConfirmAccountMailInterface;
use App\Domain\User\TokenTypeEnum;

final readonly class SendConfirmationMailCommandHandler
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ConfirmAccountMailInterface $confirmAccountMail,
    ) {
    }

    public function __invoke(SendConfirmationMailCommand $command): void
    {
        $token = $this->commandBus->handle(
            new CreateTokenCommand(
                $command->email,
                TokenTypeEnum::CONFIRM_ACCOUNT->value,
            ),
        );

        $this->confirmAccountMail->send($command->email, $token);
    }
}

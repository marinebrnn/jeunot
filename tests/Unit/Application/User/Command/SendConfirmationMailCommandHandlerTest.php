<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\CommandBusInterface;
use App\Application\Mail\User\ConfirmAccountMailInterface;
use App\Application\User\Command\CreateTokenCommand;
use App\Application\User\Command\SendConfirmationMailCommand;
use App\Application\User\Command\SendConfirmationMailCommandHandler;
use App\Domain\User\TokenTypeEnum;
use PHPUnit\Framework\TestCase;

final class SendConfirmationMailCommandHandlerTest extends TestCase
{
    public function testSendConfirmationLink(): void
    {
        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects(self::once())
            ->method('handle')
            ->with(new CreateTokenCommand('mathieu@fairness.coop', TokenTypeEnum::CONFIRM_ACCOUNT->value))
            ->willReturn('myToken');

        $confirmAccountMail = $this->createMock(ConfirmAccountMailInterface::class);
        $confirmAccountMail
            ->expects(self::once())
            ->method('send')
            ->with('mathieu@fairness.coop', 'myToken');

        $handler = new SendConfirmationMailCommandHandler($commandBus, $confirmAccountMail);
        ($handler)(new SendConfirmationMailCommand('mathieu@fairness.coop'));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command\Mail;

use App\Application\CommandBusInterface;
use App\Application\MailerInterface;
use App\Application\User\Command\CreateTokenCommand;
use App\Application\User\Command\Mail\SendConfirmationMailCommand;
use App\Application\User\Command\Mail\SendConfirmationMailCommandHandler;
use App\Domain\Mail;
use App\Domain\User\Exception\UserNotFoundException;
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

        $mail = $this->createMock(MailerInterface::class);
        $mail
            ->expects(self::once())
            ->method('send')
            ->with(
                $this->equalTo(
                    new Mail(
                        address: 'mathieu@fairness.coop',
                        subject: 'confirm_user_account.subjet',
                        template: 'email/user/confirm-user-account.html.twig',
                        payload: [
                            'token' => 'myToken',
                        ],
                    ),
                ),
            );

        $handler = new SendConfirmationMailCommandHandler($commandBus, $mail);
        ($handler)(new SendConfirmationMailCommand('   Mathieu@fairness.cooP  '));
    }

    public function testUserNotFound(): void
    {
        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects(self::once())
            ->method('handle')
            ->willThrowException(new UserNotFoundException());

        $mail = $this->createMock(MailerInterface::class);
        $mail
            ->expects(self::never())
            ->method('send');

        $handler = new SendConfirmationMailCommandHandler($commandBus, $mail);
        $command = new SendConfirmationMailCommand('   Mathieu@Fairness.coop   ');
        ($handler)($command);
    }
}

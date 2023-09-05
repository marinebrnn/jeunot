<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command\Mail;

use App\Application\CommandBusInterface;
use App\Application\MailerInterface;
use App\Application\User\Command\CreateTokenCommand;
use App\Application\User\Command\Mail\SendForgotPasswordMailCommand;
use App\Application\User\Command\Mail\SendForgotPasswordMailCommandHandler;
use App\Domain\Mail;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\TokenTypeEnum;
use PHPUnit\Framework\TestCase;

final class SendForgotPasswordMailCommandHandlerTest extends TestCase
{
    public function testSendConfirmationLink(): void
    {
        $commandBus = $this->createMock(CommandBusInterface::class);
        $commandBus
            ->expects(self::once())
            ->method('handle')
            ->with(new CreateTokenCommand('mathieu@fairness.coop', TokenTypeEnum::FORGOT_PASSWORD->value))
            ->willReturn('myToken');

        $mail = $this->createMock(MailerInterface::class);
        $mail
            ->expects(self::once())
            ->method('send')
            ->with(
                $this->equalTo(
                    new Mail(
                        address: 'mathieu@fairness.coop',
                        subject: 'forgot_password.subjet',
                        template: 'email/user/forgot-password.html.twig',
                        payload: [
                            'token' => 'myToken',
                        ],
                    ),
                ),
            );

        $handler = new SendForgotPasswordMailCommandHandler($commandBus, $mail);
        $command = new SendForgotPasswordMailCommand();
        $command->email = '   Mathieu@Fairness.coop   ';
        ($handler)($command);
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

        $handler = new SendForgotPasswordMailCommandHandler($commandBus, $mail);
        $command = new SendForgotPasswordMailCommand();
        $command->email = '   Mathieu@Fairness.coop   ';
        ($handler)($command);
    }
}

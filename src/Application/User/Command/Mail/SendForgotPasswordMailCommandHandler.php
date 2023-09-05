<?php

declare(strict_types=1);

namespace App\Application\User\Command\Mail;

use App\Application\CommandBusInterface;
use App\Application\MailerInterface;
use App\Application\User\Command\CreateTokenCommand;
use App\Domain\Mail;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\TokenTypeEnum;

final readonly class SendForgotPasswordMailCommandHandler
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(SendForgotPasswordMailCommand $command): void
    {
        $email = trim(strtolower($command->email));

        try {
            $token = $this->commandBus->handle(
                new CreateTokenCommand(
                    $email,
                    TokenTypeEnum::FORGOT_PASSWORD->value,
                ),
            );

            $this->mailer->send(
                new Mail(
                    address: $email,
                    subject: 'forgot_password.subjet',
                    template: 'email/user/forgot-password.html.twig',
                    payload: [
                        'token' => $token,
                    ],
                ),
            );
        } catch (UserNotFoundException) {
            // Do nothing.
        }
    }
}

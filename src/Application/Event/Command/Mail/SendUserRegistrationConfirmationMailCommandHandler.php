<?php

declare(strict_types=1);

namespace App\Application\Event\Command\Mail;

use App\Application\MailerInterface;
use App\Domain\Mail;

final readonly class SendUserRegistrationConfirmationMailCommandHandler
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(SendUserRegistrationConfirmationMailCommand $command): void
    {
        $this->mailer->send(
            new Mail(
                address: $command->email,
                subject: 'events.user_registration.subjet',
                template: 'email/events/user-registration-confirmation.html.twig',
                payload: [
                    'event' => $command->event,
                ],
            ),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Command\Mail;

use App\Application\Event\Command\Mail\SendUserRegistrationConfirmationMailCommand;
use App\Application\Event\Command\Mail\SendUserRegistrationConfirmationMailCommandHandler;
use App\Application\MailerInterface;
use App\Domain\Event\Event;
use App\Domain\Mail;
use PHPUnit\Framework\TestCase;

final class SendUserRegistrationConfirmationMailCommandHandlerTest extends TestCase
{
    public function testSend(): void
    {
        $event = $this->createMock(Event::class);
        $mail = $this->createMock(MailerInterface::class);
        $mail
            ->expects(self::once())
            ->method('send')
            ->with(
                $this->equalTo(
                    new Mail(
                        address: 'mathieu@fairness.coop',
                        subject: 'events.user_registration.subjet',
                        template: 'email/events/user-registration-confirmation.html.twig',
                        payload: [
                            'event' => $event,
                        ],
                    ),
                ),
            );

        $handler = new SendUserRegistrationConfirmationMailCommandHandler($mail);
        ($handler)(new SendUserRegistrationConfirmationMailCommand('mathieu@fairness.coop', $event));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Command\Mail;

use App\Application\DateUtilsInterface;
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
        $startDate = new \DateTime('2023-09-13 19:00:00');
        $endDate = new \DateTime('2023-09-14 21:00:00');

        $event = $this->createMock(Event::class);
        $event->expects(self::once())->method('getStartDate')->willReturn($startDate);
        $event->expects(self::once())->method('getEndDate')->willReturn($endDate);

        $mail = $this->createMock(MailerInterface::class);
        $dateUtils = $this->createMock(DateUtilsInterface::class);

        $dateUtils
            ->expects(self::once())
            ->method('getDaysInterval')
            ->with($startDate, $endDate)
            ->willReturn(1);

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
                            'isOverSeveralDays' => true,
                        ],
                    ),
                ),
            );

        $handler = new SendUserRegistrationConfirmationMailCommandHandler($mail, $dateUtils);
        ($handler)(new SendUserRegistrationConfirmationMailCommand('mathieu@fairness.coop', $event));
    }
}

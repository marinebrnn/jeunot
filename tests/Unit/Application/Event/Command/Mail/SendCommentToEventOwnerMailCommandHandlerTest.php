<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Event\Command\Mail;

use App\Application\Event\Command\Mail\SendCommentToEventOwnerMailCommand;
use App\Application\Event\Command\Mail\SendCommentToEventOwnerMailCommandHandler;
use App\Application\MailerInterface;
use App\Domain\Event\Event;
use App\Domain\Mail;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

final class SendCommentToEventOwnerMailCommandHandlerTest extends TestCase
{
    public function testSend(): void
    {
        $owner = $this->createMock(User::class);
        $owner
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');
        $attendee = $this->createMock(User::class);
        $event = $this->createMock(Event::class);
        $event
            ->expects(self::once())
            ->method('getOwner')
            ->willReturn($owner);
        $mail = $this->createMock(MailerInterface::class);
        $mail
            ->expects(self::once())
            ->method('send')
            ->with(
                $this->equalTo(
                    new Mail(
                        address: 'mathieu@fairness.coop',
                        subject: 'events.comment.subjet',
                        template: 'email/events/owner-comment.html.twig',
                        payload: [
                            'comment' => 'Ceci est mon commentaire',
                            'event' => $event,
                            'attendee' => $attendee,
                        ],
                    ),
                ),
            );

        $handler = new SendCommentToEventOwnerMailCommandHandler($mail);
        ($handler)(new SendCommentToEventOwnerMailCommand($event, $attendee, 'Ceci est mon commentaire'));
    }
}

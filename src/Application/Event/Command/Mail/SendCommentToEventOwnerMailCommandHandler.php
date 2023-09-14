<?php

declare(strict_types=1);

namespace App\Application\Event\Command\Mail;

use App\Application\MailerInterface;
use App\Domain\Mail;

final readonly class SendCommentToEventOwnerMailCommandHandler
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(SendCommentToEventOwnerMailCommand $command): void
    {
        $this->mailer->send(
            new Mail(
                address: $command->event->getOwner()->getEmail(),
                subject: 'events.comment.subjet',
                template: 'email/events/owner-comment.html.twig',
                payload: [
                    'comment' => $command->comment,
                    'event' => $command->event,
                    'attendee' => $command->attendee,
                ],
            ),
        );
    }
}

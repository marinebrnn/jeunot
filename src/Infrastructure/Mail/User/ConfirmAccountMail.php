<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail\User;

use App\Application\Mail\User\ConfirmAccountMailInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ConfirmAccountMail implements ConfirmAccountMailInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer,
    ) {
    }

    public function send(string $email, string $token): void
    {
        $this->mailer->send(
            (new TemplatedEmail())
                ->to(new Address($email))
                ->subject($this->translator->trans('confirm_user_account.subjet', [], 'emails'))
                ->htmlTemplate('email/register/confirm-user-account.html.twig')
                ->context([
                    'token' => $token,
                ]),
        );
    }
}

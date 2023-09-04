<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mail\User;

use App\Infrastructure\Mail\User\ConfirmAccountMail;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ConfirmAccountMailTest extends TestCase
{
    public function testSend(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $mailer = $this->createMock(MailerInterface::class);

        $translator
            ->expects(self::once())
            ->method('trans')
            ->with('confirm_user_account.subjet', [], 'emails')
            ->willReturn('Confirmez votre compte');

        $mailer
            ->expects(self::once())
            ->method('send')
            ->with(
                (new TemplatedEmail())
                    ->to(new Address('mathieu@fairness.coop'))
                    ->subject('Confirmez votre compte')
                    ->htmlTemplate('email/register/confirm-user-account.html.twig')
                    ->context([
                        'token' => 'myToken',
                    ]),
            );

        $mail = new ConfirmAccountMail($translator, $mailer);
        $mail->send('mathieu@fairness.coop', 'myToken');
    }
}

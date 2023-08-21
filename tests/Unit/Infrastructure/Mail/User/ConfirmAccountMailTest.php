<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Mail\User;

use App\Infrastructure\Mail\User\ConfirmAccountMail;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ConfirmAccountMailTest extends TestCase
{
    public function testSend(): void
    {
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $translator = $this->createMock(TranslatorInterface::class);
        $mailer = $this->createMock(MailerInterface::class);
        $uriSigner = $this->createMock(UriSigner::class);
        $expirationDate = $this->createMock(\DateTimeImmutable::class);
        $expirationDate
            ->expects(self::once())
            ->method('getTimestamp')
            ->willReturn(1692568800);

        $translator
            ->expects(self::once())
            ->method('trans')
            ->with('verify_user_account.subjet', [], 'emails')
            ->willReturn('Confirmez votre compte');

        $urlGenerator
            ->expects(self::once())
            ->method('generate')
            ->with('app_register_confirm_account', [
                'email' => 'mathieu@fairness.coop',
                'expirationDate' => 1692568800,
            ], UrlGeneratorInterface::ABSOLUTE_URL)
            ->willReturn('http://jeunot.localhost/verify-user-account?email=mathieu@fairness.coop&expirationDate=1693488550');

        $uriSigner
            ->expects(self::once())
            ->method('sign')
            ->with('http://jeunot.localhost/verify-user-account?email=mathieu@fairness.coop&expirationDate=1693488550')
            ->willReturn('http://jeunot.localhost/verify-user-account?_hash=DWwaEo0%2FmWBTObQbc%2FjzrEbZ7GUr9m6qkhLGv%2BJx8UQ%3D&email=mathieu@fairness.coop&expirationDate=1693488550');

        $mailer
            ->expects(self::once())
            ->method('send')
            ->with(
                (new TemplatedEmail())
                    ->to(new Address('mathieu@fairness.coop'))
                    ->subject('Confirmez votre compte')
                    ->htmlTemplate('email/register/verify-user-account.html.twig')
                    ->context([
                        'uri' => 'http://jeunot.localhost/verify-user-account?_hash=DWwaEo0%2FmWBTObQbc%2FjzrEbZ7GUr9m6qkhLGv%2BJx8UQ%3D&email=mathieu@fairness.coop&expirationDate=1693488550',
                    ]),
            );

        $mail = new ConfirmAccountMail(
            $urlGenerator,
            $translator,
            $mailer,
            $uriSigner,
        );
        $mail->send('mathieu@fairness.coop', $expirationDate);
    }
}

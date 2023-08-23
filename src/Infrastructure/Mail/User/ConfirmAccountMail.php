<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail\User;

use App\Application\Mail\User\ConfirmAccountMailInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ConfirmAccountMail implements ConfirmAccountMailInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
        private readonly MailerInterface $mailer,
        private readonly UriSigner $uriSigner,
    ) {
    }

    public function send(string $email, \DateTimeInterface $expirationDate): void
    {
        $uri = $this->uriSigner->sign($this->urlGenerator->generate('app_register_confirm_account', [
            'email' => $email,
            'expirationDate' => $expirationDate->getTimestamp(),
        ], UrlGeneratorInterface::ABSOLUTE_URL));

        $this->mailer->send(
            (new TemplatedEmail())
                ->to(new Address($email))
                ->subject($this->translator->trans('verify_user_account.subjet', [], 'emails'))
                ->htmlTemplate('email/register/verify-user-account.html.twig')
                ->context([
                    'uri' => $uri,
                ]),
        );
    }
}

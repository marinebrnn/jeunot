<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Register;

use App\Application\CommandBusInterface;
use App\Application\User\Command\Mail\SendConfirmationMailCommand;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ResendConfirmationMailController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('/register/resend-confirmation-mail', name: 'app_resend_confirmation_mail', methods: ['POST'])]
    public function __invoke(#[MapQueryParameter] string $email): Response
    {
        $this->commandBus->dispatchAsync(new SendConfirmationMailCommand($email));

        return new RedirectResponse(
            $this->urlGenerator->generate('app_register_succeeded', ['email' => $email]),
        );
    }
}

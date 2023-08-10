<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterSucceededController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
    ) {
    }

    #[Route('/register/succeeded', name: 'app_register_succeeded', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response(
            $this->twig->render('security/register_succeeded.html.twig'),
        );
    }
}

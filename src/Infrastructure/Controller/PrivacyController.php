<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PrivacyController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
    ) {
    }

    #[Route('/privacy', name: 'app_privacy', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('privacy.html.twig'));
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CguController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
    ) {
    }

    #[Route('/cgu', name: 'app_cgu', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('cgu.html.twig'));
    }
}

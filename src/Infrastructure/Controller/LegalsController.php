<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LegalsController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
    ) {
    }

    #[Route('/legals', name: 'app_legals', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('legals.html.twig'));
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController
{
    public function __construct(
        private readonly \Twig\Environment $twig,
    ) {
    }

    #[Route(name: 'app_dashboard', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response($this->twig->render('app/index.html.twig'));
    }
}

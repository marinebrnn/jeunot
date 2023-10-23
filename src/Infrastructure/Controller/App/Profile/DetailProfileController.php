<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\App\Profile;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class DetailProfileController
{
    public function __construct(
        private \Twig\Environment $twig,
    ) {
    }

    #[Route(
        '/profile/{uuid}',
        name: 'app_profile_detail',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['GET'],
    )]
    public function __invoke(string $uuid): Response
    {
        return new Response(
            content: $this->twig->render(
                name: 'app/profile/detail.html.twig',
                context: [
                ],
            ),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\App\Profile;

use App\Application\QueryBusInterface;
use App\Application\User\Query\GetUserProfileQuery;
use App\Domain\User\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class DetailProfileController
{
    public function __construct(
        private QueryBusInterface $queryBus,
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
        try {
            $user = $this->queryBus->handle(new GetUserProfileQuery($uuid));
        } catch (UserNotFoundException) {
            throw new NotFoundHttpException();
        }

        return new Response(
            content: $this->twig->render(
                name: 'app/profile/detail.html.twig',
                context: [
                    'user' => $user,
                    'uuid' => $uuid,
                ],
            ),
        );
    }
}

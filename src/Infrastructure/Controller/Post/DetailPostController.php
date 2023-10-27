<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Post;

use App\Application\Post\Query\GetDetailedPostQuery;
use App\Application\QueryBusInterface;
use App\Domain\Post\Exception\PostNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final readonly class DetailPostController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route(
        '/posts/{slug}',
        name: 'app_posts_detail',
        methods: ['GET'],
    )]
    public function __invoke(string $slug): Response
    {
        try {
            $post = $this->queryBus->handle(new GetDetailedPostQuery($slug));
        } catch (PostNotFoundException) {
            throw new NotFoundHttpException();
        }

        return new Response(
            content: $this->twig->render(
                name: 'posts/detail.html.twig',
                context : [
                    'post' => $post,
                ],
            ),
        );
    }
}

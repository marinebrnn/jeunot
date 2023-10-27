<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Post;

use App\Application\Post\Query\GetPublishedPostsQuery;
use App\Application\QueryBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

final readonly class ListPostsController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route(
        '/posts/{page}',
        name: 'app_posts_list',
        priority: 1,
        requirements: ['page' => '\d+'],
        methods: ['GET'],
    )]
    public function __invoke(Request $request, int $page = 1): Response
    {
        $pageSize = min($request->query->getInt('pageSize', 20), 50);
        if (0 === $pageSize) {
            throw new BadRequestHttpException();
        }

        $paginatedPosts = $this->queryBus->handle(new GetPublishedPostsQuery($page, $pageSize));

        return new Response(
            content: $this->twig->render(
                name: 'posts/list.html.twig',
                context : [
                    'paginatedPosts' => $paginatedPosts,
                ],
            ),
        );
    }
}

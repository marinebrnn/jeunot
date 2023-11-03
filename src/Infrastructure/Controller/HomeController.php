<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Event\Query\GetActiveEventsQuery;
use App\Application\QueryBusInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomeController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function __invoke(): Response
    {
        $paginatedEvents = $this->queryBus->handle(new GetActiveEventsQuery(1, 3));

        return new Response($this->twig->render('index.html.twig', [
            'paginatedEvents' => $paginatedEvents,
        ]));
    }
}

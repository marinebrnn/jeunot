<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Event;

use App\Application\Event\Query\GetRegisterSucceededEventQuery;
use App\Application\QueryBusInterface;
use App\Domain\Event\Exception\EventNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final readonly class RegisterUserForAnEventSucceededController
{
    public function __construct(
        private \Twig\Environment $twig,
        private QueryBusInterface $queryBus,
    ) {
    }

    #[Route(
        '/events/{uuid}/register/succeeded',
        name: 'app_events_register_succeeded',
        requirements: ['uuid' => Requirement::UUID],
        methods: ['GET'],
    )]
    public function __invoke(string $uuid): Response
    {
        try {
            $event = $this->queryBus->handle(new GetRegisterSucceededEventQuery($uuid));
        } catch (EventNotFoundException) {
            throw new NotFoundHttpException();
        }

        return new Response(
            content: $this->twig->render(
                name: 'events/register_succeeded.html.twig',
                context : [
                    'event' => $event,
                ],
            ),
        );
    }
}

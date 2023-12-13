<?php

declare(strict_types=1);

namespace App\Application\Event\Query;

use App\Application\Event\View\RegisterSucceededEventView;
use App\Domain\Event\Exception\EventNotFoundException;
use App\Domain\Event\Repository\EventRepositoryInterface;

final class GetRegisterSucceededEventQueryHandler
{
    public function __construct(
        private EventRepositoryInterface $eventRepository,
    ) {
    }

    public function __invoke(GetRegisterSucceededEventQuery $query): RegisterSucceededEventView
    {
        $event = $this->eventRepository->findRegisterSucceededEvent($query->uuid);

        if (!$event) {
            throw new EventNotFoundException();
        }

        return new RegisterSucceededEventView(
            uuid: $event['uuid'],
            title: $event['title'],
            ownerEmail: $event['ownerEmail'],
        );
    }
}

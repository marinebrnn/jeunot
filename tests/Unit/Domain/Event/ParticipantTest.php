<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\Event\Event;
use App\Domain\Event\Participant;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

final class ParticipantTest extends TestCase
{
    public function testGetters(): void
    {
        $user = $this->createMock(User::class);
        $event = $this->createMock(Event::class);
        $registeredOn = new \DateTime('2023-08-25');

        $participant = new Participant(
            '9cebe00d-04d8-48da-89b1-059f6b7bfe44',
            $user,
            $event,
            $registeredOn,
        );

        $this->assertSame('9cebe00d-04d8-48da-89b1-059f6b7bfe44', $participant->getUuid());
        $this->assertSame($user, $participant->getUser());
        $this->assertSame($event, $participant->getEvent());
        $this->assertSame($registeredOn, $participant->getRegisteredOn());
    }
}

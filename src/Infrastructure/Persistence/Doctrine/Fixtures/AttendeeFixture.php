<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\Event\Attendee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class AttendeeFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $attendee1 = new Attendee(
            'eb70ad27-77dc-4d4e-b066-cfb09d49d311',
            $this->getReference('user'),
            $this->getReference('fullyBookedEvent'),
            new \DateTime('2023-09-14 09:00:00'),
        );
        $attendee2 = new Attendee(
            'f5cbccb8-2748-4be5-ba47-7fb34181d2e4',
            $this->getReference('admin'),
            $this->getReference('fullyBookedEvent'),
            new \DateTime('2023-09-14 08:00:00'),
        );
        $attendee3 = new Attendee(
            '79710984-2574-48a7-821b-3f7966a8ff4d',
            $this->getReference('admin'),
            $this->getReference('eventWithOnlyOnePlaceAvailable'),
            new \DateTime('2023-09-20 08:00:00'),
        );

        $manager->persist($attendee1);
        $manager->persist($attendee2);
        $manager->persist($attendee3);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EventFixture::class,
            UserFixture::class,
        ];
    }
}

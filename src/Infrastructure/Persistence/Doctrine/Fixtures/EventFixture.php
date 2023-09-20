<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\Event\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class EventFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $publishedEvent = new Event(
            'f1f992d3-3cf5-4eb2-9b83-f112b7234613',
            $this->getReference('admin'),
        );
        $publishedEvent->setTitle('Balade et pique-nique en forêt de Chevreuse');
        $publishedEvent->setDescription('Lorem ipsum');
        $publishedEvent->setInitialAvailablePlaces(10);
        $publishedEvent->setStartDate(new \DateTime('2023-09-13 09:00:00'));
        $publishedEvent->setEndDate(new \DateTime('2023-09-13 18:00:00'));
        $publishedEvent->setPublished(true);
        $publishedEvent->setLocation('Saint Remy les Chevreuses');

        $fullyBookedEvent = new Event(
            '89f72b23-55e9-4975-b640-da24890095b7',
            $this->getReference('admin'),
        );
        $fullyBookedEvent->setTitle('Balade à vélo en tandem');
        $fullyBookedEvent->setDescription('Lorem ipsum');
        $fullyBookedEvent->setInitialAvailablePlaces(2);
        $fullyBookedEvent->setStartDate(new \DateTime('2023-09-14 20:00:00'));
        $fullyBookedEvent->setEndDate(new \DateTime('2023-09-14 21:00:00'));
        $fullyBookedEvent->setPublished(true);
        $fullyBookedEvent->setLocation('Paris');

        $eventWithOnlyOnePlaceAvailable = new Event(
            'a6ed00e5-a566-4115-a547-01378baba9b1',
            $this->getReference('admin'),
        );
        $eventWithOnlyOnePlaceAvailable->setTitle('Dîner au restaurant');
        $eventWithOnlyOnePlaceAvailable->setDescription('Lorem ipsum');
        $eventWithOnlyOnePlaceAvailable->setInitialAvailablePlaces(2);
        $eventWithOnlyOnePlaceAvailable->setStartDate(new \DateTime('2023-09-20 20:00:00'));
        $eventWithOnlyOnePlaceAvailable->setEndDate(new \DateTime('2023-09-20 21:00:00'));
        $eventWithOnlyOnePlaceAvailable->setPublished(true);
        $eventWithOnlyOnePlaceAvailable->setLocation('Paris');

        $unpublishedEvent = new Event(
            '8d9947e2-02c1-4064-b385-2749b85f1f2d',
            $this->getReference('user'),
        );
        $unpublishedEvent->setTitle('Soirée dansante');
        $unpublishedEvent->setDescription('Lorem ipsum');
        $unpublishedEvent->setInitialAvailablePlaces(20);
        $unpublishedEvent->setStartDate(new \DateTime('2023-09-20 19:00:00'));
        $unpublishedEvent->setEndDate(new \DateTime('2023-09-20 21:00:00'));
        $unpublishedEvent->setPublished(false);
        $unpublishedEvent->setLocation('Paris 75018');

        $manager->persist($publishedEvent);
        $manager->persist($unpublishedEvent);
        $manager->persist($fullyBookedEvent);
        $manager->persist($eventWithOnlyOnePlaceAvailable);
        $manager->flush();

        $this->addReference('fullyBookedEvent', $fullyBookedEvent);
        $this->addReference('eventWithOnlyOnePlaceAvailable', $eventWithOnlyOnePlaceAvailable);
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}

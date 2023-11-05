<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\Event\Event;
use App\Domain\Event\Tag;
use App\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class EventTest extends TestCase
{
    public function testGetters(): void
    {
        $startDate = new \DateTime('2023-08-25 08:00:00');
        $endDate = new \DateTime('2023-08-25 18:00:00');
        $user = $this->createMock(User::class);
        $tag1 = $this->createMock(Tag::class);
        $tag2 = $this->createMock(Tag::class);

        $event = new Event(
            uuid: '9cebe00d-04d8-48da-89b1-059f6b7bfe44',
            owner: $user,
        );
        $event->setTitle('Balade et pique-nique en forêt de Chevreuse');
        $event->setDescription('Lorem ipsum dolor sit amet, consectetuer adipiscing elit.');
        $event->setInitialAvailablePlaces(100);
        $event->setStartDate($startDate);
        $event->setEndDate($endDate);
        $event->setPublished(true);
        $event->setLocation('Saint Remy Les Chevreuses');
        $event->setPicture('/path/to/my/file');

        $this->assertSame('9cebe00d-04d8-48da-89b1-059f6b7bfe44', $event->getUuid());
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $event->getTitle());
        $this->assertSame('Lorem ipsum dolor sit amet, consectetuer adipiscing elit.', $event->getDescription());
        $this->assertSame(100, $event->getInitialAvailablePlaces());
        $this->assertSame($startDate, $event->getStartDate());
        $this->assertSame($endDate, $event->getEndDate());
        $this->assertSame($user, $event->getOwner());
        $this->assertSame('/path/to/my/file', $event->getPicture());
        $this->assertSame('Saint Remy Les Chevreuses', $event->getLocation());
        $this->assertTrue($event->isPublished());
        $this->assertEquals(new ArrayCollection([]), $event->getTags());

        $event->addTag($tag1);
        $event->addTag($tag1); // Test duplicate
        $event->addTag($tag2);

        $this->assertEquals(new ArrayCollection([$tag1, $tag2]), $event->getTags());

        $event->removeTag($tag1);

        $this->assertEquals(new ArrayCollection([1 => $tag2]), $event->getTags());

        // Hack linked to easy admin to avoid deleting an existing image when editing form
        $event->setPicture(null);
        $this->assertSame('/path/to/my/file', $event->getPicture());
    }
}

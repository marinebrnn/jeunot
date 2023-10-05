<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Event;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class ListEventsControllerTest extends AbstractWebTestCase
{
    public function testGetEvents(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/events?pageSize=20');
        $li = $crawler->filter('[data-testid="event-list"] li');
        $eventTitle = $li->filter('h3');
        $attendees = $li->filter('[data-testid="event-attendees"]');
        $location = $li->filter('[data-testid="event-location"]');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Événements', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Événements - Jeunot', $crawler);
        $this->assertSame(3, $li->count());

        $this->assertSame('1 participant', $attendees->eq(0)->text());
        $this->assertSame('Dîner au restaurant', $eventTitle->eq(0)->text());
        $this->assertSame('http://localhost/events/a6ed00e5-a566-4115-a547-01378baba9b1', $eventTitle->eq(0)->filter('a')->link()->getUri());
        $this->assertSame('Paris', $li->eq(0)->filter('figcaption')->text());

        $this->assertSame('2 participants', $attendees->eq(1)->text());
        $this->assertSame('Balade à vélo en tandem', $eventTitle->eq(1)->text());
        $this->assertSame('http://localhost/events/89f72b23-55e9-4975-b640-da24890095b7', $eventTitle->eq(1)->filter('a')->link()->getUri());
        $this->assertSame('Paris', $location->eq(1)->text());

        $this->assertSame('Aucun participant', $attendees->eq(2)->text());
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $eventTitle->eq(2)->text());
        $this->assertSame('http://localhost/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613', $eventTitle->eq(2)->filter('a')->link()->getUri());
        $this->assertSame('Saint Remy les Chevreuses', $location->eq(2)->text());
    }

    public function testLoggedGetEvents(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/events?pageSize=20');
        $li = $crawler->filter('[data-testid="event-list"] li');
        $eventTitle = $li->filter('h3');
        $attendees = $li->filter('[data-testid="event-attendees"]');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Événements', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Événements - Jeunot', $crawler);
        $this->assertSame(3, $crawler->filter('[data-testid="event-list"] li')->count());

        $this->assertSame('1 participant - Inscrit·e !', $attendees->eq(0)->text());
        $this->assertSame('Dîner au restaurant', $eventTitle->eq(0)->text());

        $this->assertSame('2 participants - Inscrit·e !', $attendees->eq(1)->text());
        $this->assertSame('Balade à vélo en tandem', $eventTitle->eq(1)->text());

        $this->assertSame('Aucun participant', $attendees->eq(2)->text());
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $eventTitle->eq(2)->text());
    }

    public function testBadPageParameter(): void
    {
        $client = static::createClient();
        $client->request('GET', '/events/test');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testBadPageSizeParameter(): void
    {
        $client = static::createClient();

        $client->request('GET', '/events/1?pageSize=0');
        $this->assertResponseStatusCodeSame(400);

        $client->request('GET', '/events/1?pageSize=test');
        $this->assertResponseStatusCodeSame(400);
    }
}

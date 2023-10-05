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
        $li = $crawler->filter('main ul.j-raw-list > li');
        $event = $li->filter('a');
        $attendees = $li->filter('small');
        $location = $li->filter('figure');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Événements', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Événements - Jeunot', $crawler);
        $this->assertSame(3, $li->count());

        $this->assertSame('1 participant', $attendees->eq(0)->text());
        $this->assertSame('Dîner au restaurant', $event->eq(0)->text());
        $this->assertSame('http://localhost/events/a6ed00e5-a566-4115-a547-01378baba9b1', $event->eq(0)->link()->getUri());
        $this->assertSame('Paris', $location->eq(0)->text());

        $this->assertSame('2 participants', $attendees->eq(1)->text());
        $this->assertSame('http://localhost/events/89f72b23-55e9-4975-b640-da24890095b7', $event->eq(1)->link()->getUri());
        $this->assertSame('Balade à vélo en tandem', $event->eq(1)->text());
        $this->assertSame('Paris', $location->eq(1)->text());

        $this->assertSame('Aucun participant', $attendees->eq(2)->text());
        $this->assertSame('http://localhost/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613', $event->eq(2)->link()->getUri());
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $event->eq(2)->text());
        $this->assertSame('Saint Remy les Chevreuses', $location->eq(2)->text());
    }

    public function testLoggedGetEvents(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/events?pageSize=20');
        $li = $crawler->filter('main ul.j-raw-list > li');
        $event = $li->filter('a');
        $attendees = $li->filter('small');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Événements', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Événements - Jeunot', $crawler);
        $this->assertSame(3, $crawler->filter('main ul.j-raw-list > li')->count());

        $this->assertSame('1 participant - Inscrit·e !', $attendees->eq(0)->text());
        $this->assertSame('Dîner au restaurant', $event->eq(0)->text());

        $this->assertSame('2 participants - Inscrit·e !', $attendees->eq(1)->text());
        $this->assertSame('Balade à vélo en tandem', $event->eq(1)->text());

        $this->assertSame('Aucun participant', $attendees->eq(2)->text());
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $event->eq(2)->text());
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

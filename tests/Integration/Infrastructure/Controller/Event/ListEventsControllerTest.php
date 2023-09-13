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
        $li = $crawler->filter('ul > li');

        $this->assertSecurityHeaders();
        $this->assertSame('Événements', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Événements - Jeunot', $crawler);
        $this->assertSame(1, $li->count());
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse - Saint Remy les Chevreuses - 13/09/2023 - Aucun inscrit', $li->text());
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

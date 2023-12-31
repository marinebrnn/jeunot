<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Event;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class DetailEventControllerTest extends AbstractWebTestCase
{
    public function testEvent(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Balade et pique-nique en forêt de Chevreuse - Jeunot', $crawler);
        $this->assertSame('Saint Remy les Chevreuses Balade et pique-nique en forêt de Chevreuse mercredi 13 septembre 2023 09:00 - 18:00 Saint Remy les Chevreuses Lorem ipsum Cet événement est organisé par Mathieu 33 ans, Saint Ouen 0 inscrits - 10 places disponibles S\'inscrire à cet événement', $crawler->filter('[data-testid="event"]')->text());
        $this->assertSame('http://localhost/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/register', $crawler->filter('[data-testid="register-link"]')->link()->getUri());
        $this->assertSame('Cet événement est organisé par', $crawler->filter('h2')->eq(0)->text());

        $this->assertSame('Autres événements à venir', $crawler->filter('h2')->eq(1)->text());
        $otherEvents = $crawler->filter('[data-testid=event-list] [role=listitem]');
        $this->assertSame(2, $otherEvents->count());

        $this->assertSame('Pourquoi Jeunot ?', $crawler->filter('h2')->eq(2)->text());
    }

    public function testFullyBookedEvent(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/events/89f72b23-55e9-4975-b640-da24890095b7');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Balade à vélo en tandem', $crawler->filter('h1')->text());
        $this->assertSame('Paris Balade à vélo en tandem jeudi 14 septembre 2023 20:00 - 21:00 Paris Lorem ipsum Cet événement est organisé par Mathieu 33 ans, Saint Ouen 2 inscrits - 0 places disponibles Vous êtes inscrit·e à cet événement. Annuler mon inscription', $crawler->filter('[data-testid="event"]')->text());
        $this->assertSame(0, $crawler->filter('[data-testid="register-link"]')->count()); // No register link
    }

    public function testEventWithoutAuthentication(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Balade et pique-nique en forêt de Chevreuse - Jeunot', $crawler);
        $this->assertSame('Saint Remy les Chevreuses Balade et pique-nique en forêt de Chevreuse mercredi 13 septembre 2023 09:00 - 18:00 Saint Remy les Chevreuses Lorem ipsum Cet événement est organisé par Mathieu 33 ans, Saint Ouen 0 inscrits - 10 places disponibles Vous devez être connecté·e pour vous inscrire et recevoir les informations de rendez-vous. Créer un compte', $crawler->filter('[data-testid="event"]')->text());
        $this->assertSame('http://localhost/register', $crawler->filter('[data-testid="event"]')->selectLink('Créer un compte')->link()->getUri());
        $this->assertSame(0, $crawler->filter('[data-testid="register-link"]')->count()); // No register link
    }

    public function testEventNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/events/54bbf639-5049-4cd5-b0fb-5c4880c17e5f');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testEventNotPublished(): void
    {
        $client = static::createClient();
        $client->request('GET', '/events/8d9947e2-02c1-4064-b385-2749b85f1f2d');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testBadParameter(): void
    {
        $client = static::createClient();

        $client->request('GET', '/events/abc');
        $this->assertResponseStatusCodeSame(404);
    }
}

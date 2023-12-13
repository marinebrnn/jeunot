<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Event;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class RegisterUserForAnEventSucceededControllerTest extends AbstractWebTestCase
{
    public function testSucceeded(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/register/succeeded');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Vous êtes bien inscrit·e ! 👍', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Vous êtes bien inscrit·e ! - Jeunot', $crawler);

        $contactLink = $crawler->selectLink("Contacter l'organisateur");
        $this->assertSame('mailto:mathieu@fairness.coop', $contactLink->link()->getUri());

        $unregisterLink = $crawler->selectButton('Annuler mon inscription');
        $this->assertStringEndsWith('/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/unregister', $unregisterLink->form()->getUri());
    }

    public function testUnpublishedEvent(): void
    {
        $client = $this->login();
        $client->request('GET', '/events/8d9947e2-02c1-4064-b385-2749b85f1f2d/register/succeeded');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testEventNotFound(): void
    {
        $client = $this->login();
        $client->request('GET', '/events/54bbf639-5049-4cd5-b0fb-5c4880c17e5f/register/succeeded');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testBadParameter(): void
    {
        $client = $this->login();
        $client->request('GET', '/events/abc/register/succeeded');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/register/succeeded');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

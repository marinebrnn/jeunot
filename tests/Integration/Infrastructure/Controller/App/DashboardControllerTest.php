<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\App;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class DashboardControllerTest extends AbstractWebTestCase
{
    public function testDashboard(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Bonjour Mathieu !', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Mon espace - Jeunot', $crawler);

        $li = $crawler->filter('[data-testid="event-list"] li');
        $eventTitle = $li->filter('h3');

        $this->assertSame(2, $crawler->filter('[data-testid="event-list"] li')->count());
        $this->assertSame('Dîner au restaurant', $eventTitle->eq(0)->text());
        $this->assertSame('Balade à vélo en tandem', $eventTitle->eq(1)->text());
    }

    public function testWithoutAuthenticatedUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/app');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

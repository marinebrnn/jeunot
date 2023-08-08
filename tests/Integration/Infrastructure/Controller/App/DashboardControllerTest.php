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
        $this->assertSame('DASHBOARD', $crawler->filter('h1')->text());
        $this->assertMetaTitle('DASHBOARD - Jeunot', $crawler);
    }

    public function testWithoutAuthenticatedUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/app');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

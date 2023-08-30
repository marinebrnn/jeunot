<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Admin;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class DashboardControllerTest extends AbstractWebTestCase
{
    public function testDashboard(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Jeunot', $crawler->filter('span.logo-custom')->text());
    }

    public function testDashboardWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }

    public function testDashboardWithRoleUser(): void
    {
        $client = $this->login('gregory.pelletier@fairness.coop');
        $client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(403);
    }
}

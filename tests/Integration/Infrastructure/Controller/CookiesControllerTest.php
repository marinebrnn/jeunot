<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller;

final class CookiesControllerTest extends AbstractWebTestCase
{
    public function testCookies(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/cookies');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Politique de gestion des cookies', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Politique de gestion des cookies - Jeunot', $crawler);
    }
}

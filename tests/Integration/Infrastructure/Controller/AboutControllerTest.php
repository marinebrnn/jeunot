<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller;

final class AboutControllerTest extends AbstractWebTestCase
{
    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('À propos', $crawler->filter('h1')->text());
    }
}

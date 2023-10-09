<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller;

final class HomeControllerTest extends AbstractWebTestCase
{
    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('La communauté où les 60 ans et plus se rencontrent !', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Jeunot', $crawler);
    }
}

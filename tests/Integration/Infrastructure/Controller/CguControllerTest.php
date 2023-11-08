<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller;

final class CguControllerTest extends AbstractWebTestCase
{
    public function testCgu(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/cgu');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Conditions Générales d\'utilisation', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Conditions Générales d\'utilisation - Jeunot', $crawler);
    }
}

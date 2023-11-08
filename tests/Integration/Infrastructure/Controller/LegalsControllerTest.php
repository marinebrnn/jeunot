<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller;

final class LegalsControllerTest extends AbstractWebTestCase
{
    public function testLegals(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/legals');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Mentions légales', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Mentions légales - Jeunot', $crawler);
    }
}

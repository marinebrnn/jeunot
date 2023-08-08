<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller;

final class HomeControllerTest extends AbstractWebTestCase
{
    public function testHome(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('HOME', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Jeunot', $crawler);
    }
}

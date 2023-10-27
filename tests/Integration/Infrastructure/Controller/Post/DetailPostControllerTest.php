<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Post;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class DetailPostControllerTest extends AbstractWebTestCase
{
    public function testPost(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/posts/lancement-de-la-plateforme-jeunot');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Lancement de la plateforme Jeunot', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Lancement de la plateforme Jeunot - Jeunot', $crawler);
    }

    public function testPostNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/posts/article-en-cours-de-redaction');

        $this->assertResponseStatusCodeSame(404);
    }
}

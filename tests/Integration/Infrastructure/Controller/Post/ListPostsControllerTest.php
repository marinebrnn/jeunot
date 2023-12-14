<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Post;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class ListPostsControllerTest extends AbstractWebTestCase
{
    public function testGetPosts(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/posts?pageSize=20');

        $li = $crawler->filter('[data-testid="card-list"] [role="listitem"]');
        $postTitle = $li->filter('h3');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Articles', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Articles - Jeunot', $crawler);
        $this->assertSame(1, $li->count());

        $this->assertSame('Lancement de la plateforme Jeunot', $postTitle->eq(0)->text());
        $this->assertSame('http://localhost/posts/lancement-de-la-plateforme-jeunot', $postTitle->eq(0)->filter('a')->link()->getUri());
    }

    public function testBadPageSizeParameter(): void
    {
        $client = static::createClient();

        $client->request('GET', '/posts/1?pageSize=0');
        $this->assertResponseStatusCodeSame(400);

        $client->request('GET', '/posts/1?pageSize=test');
        $this->assertResponseStatusCodeSame(400);
    }
}

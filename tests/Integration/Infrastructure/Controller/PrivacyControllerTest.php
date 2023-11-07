<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller;

final class PrivacyControllerTest extends AbstractWebTestCase
{
    public function testPrivacy(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/privacy');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Politique de confidentialité', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Politique de confidentialité - Jeunot', $crawler);
    }
}

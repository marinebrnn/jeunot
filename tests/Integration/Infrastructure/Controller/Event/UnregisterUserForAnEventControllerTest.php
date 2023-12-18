<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Event;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;
use App\Tests\SessionHelper;

final class UnregisterUserForAnEventControllerTest extends AbstractWebTestCase
{
    use SessionHelper;

    public function testUnregister(): void
    {
        $client = $this->login();
        $client->request('POST', '/events/89f72b23-55e9-4975-b640-da24890095b7/unregister', [
            'token' => $this->generateCsrfToken($client, 'unregister-user-for-an-event'),
        ]);
        $this->assertResponseRedirects('/events/89f72b23-55e9-4975-b640-da24890095b7', 302);
        $crawler = $client->followRedirect();
        $this->assertEquals(['success' => ['Votre désinscription a bien été prise en compte !']], $this->getFlashes($crawler));
    }

    public function testUnregisterFromAnEventWhereIAmNotRegistered(): void
    {
        $client = $this->login();
        $client->request('POST', '/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/unregister', [
            'token' => $this->generateCsrfToken($client, 'unregister-user-for-an-event'),
        ]);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testInvalidCsrfToken(): void
    {
        $client = $this->login();
        $client->request('POST', '/events/89f72b23-55e9-4975-b640-da24890095b7/unregister');
        $this->assertResponseStatusCodeSame(400);
    }

    public function testWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->request('POST', '/events/89f72b23-55e9-4975-b640-da24890095b7/unregister', [
            'token' => $this->generateCsrfToken($client, 'unregister-user-for-an-event'),
        ]);
        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

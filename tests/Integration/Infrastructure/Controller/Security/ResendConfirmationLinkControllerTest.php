<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Security;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class ResendConfirmationLinkControllerTest extends AbstractWebTestCase
{
    public function testResendConfirmationLink(): void
    {
        $client = static::createClient();
        $client->request('POST', '/register/resend-confirmation-link?email=mathieu@fairness.coop');

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('app_register_succeeded');
    }

    public function testMissingEmail(): void
    {
        $client = static::createClient();
        $client->request('POST', '/register/resend-confirmation-link');

        $this->assertResponseStatusCodeSame(404);
    }
}

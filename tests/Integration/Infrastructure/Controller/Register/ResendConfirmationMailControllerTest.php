<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Register;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class ResendConfirmationMailControllerTest extends AbstractWebTestCase
{
    public function testResendConfirmationMail(): void
    {
        $client = static::createClient();
        $client->request('POST', '/register/resend-confirmation-mail?email=mathieu@fairness.coop');

        $this->assertResponseStatusCodeSame(302);
        $email = $this->getMailerMessage();
        $this->assertEmailHtmlBodyContains($email, 'Pour finaliser la création de votre compte, merci de cliquer sur le lien ci-dessous:');

        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('app_register_succeeded');
    }

    public function testMissingEmail(): void
    {
        $client = static::createClient();
        $client->request('POST', '/register/resend-confirmation-mail');

        $this->assertResponseStatusCodeSame(404);
    }
}

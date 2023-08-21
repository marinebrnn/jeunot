<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Security;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;
use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RegisterConfirmAccountControllerTest extends AbstractWebTestCase
{
    public function testVerifyAccount(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $uriSigner = $container->get(UriSigner::class);
        $urlGenerator = $container->get(UrlGeneratorInterface::class);

        $url = $uriSigner->sign(
            $urlGenerator->generate('app_register_confirm_account', ['email' => 'gregory.pelletier@fairness.coop', 'expirationDate' => 1693139417], UrlGeneratorInterface::ABSOLUTE_URL),
        );
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('app_dashboard');
    }

    public function testExpiredURI(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $uriSigner = $container->get(UriSigner::class);
        $urlGenerator = $container->get(UrlGeneratorInterface::class);

        $url = $uriSigner->sign(
            $urlGenerator->generate('app_register_confirm_account', ['email' => 'mathieu@fairness.coop', 'expirationDate' => 1692774112], UrlGeneratorInterface::ABSOLUTE_URL),
        );
        $crawler = $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(400);
        $this->assertMetaTitle('Expired URI (400 Bad Request)', $crawler);
    }

    public function testUserNotFound(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $uriSigner = $container->get(UriSigner::class);
        $urlGenerator = $container->get(UrlGeneratorInterface::class);

        $url = $uriSigner->sign(
            $urlGenerator->generate('app_register_confirm_account', ['email' => 'helene@fairness.coop', 'expirationDate' => 1734940912], UrlGeneratorInterface::ABSOLUTE_URL),
        );
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(404);
    }

    public function testUnsignedUri(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register/confirm-account?email=mathieu@fairness.coop&expirationDate=1692774112');

        $this->assertResponseStatusCodeSame(400);
        $this->assertMetaTitle('Unsigned URI (400 Bad Request)', $crawler);
    }

    public function testMissingParameters(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register/confirm-account');
        $this->assertResponseStatusCodeSame(404);

        $client->request('GET', '/register/confirm-account?email=mathieu@fairness.coop');
        $this->assertResponseStatusCodeSame(404);

        $client->request('GET', '/register/confirm-account?expirationDate=1692774112');
        $this->assertResponseStatusCodeSame(404);
    }
}

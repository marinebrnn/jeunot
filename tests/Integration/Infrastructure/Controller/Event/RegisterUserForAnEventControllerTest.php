<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\Event;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class RegisterUserForAnEventControllerTest extends AbstractWebTestCase
{
    public function testRegistration(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/register');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Inscription à l\'événement', $crawler->filter('h1')->text());
        $this->assertSame('Balade et pique-nique en forêt de Chevreuse', $crawler->filter('h2')->text());
        $this->assertMetaTitle('Inscription à l\'événement - Jeunot', $crawler);

        $saveButton = $crawler->selectButton('Valider mon inscription');
        $form = $saveButton->form();
        $form['register_form[comment]'] = 'Ceci est un commentaire';
        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);

        $this->assertEmailCount(2);
        $email1 = $this->getMailerMessage(0);
        $email2 = $this->getMailerMessage(3);
        $this->assertEmailHtmlBodyContains($email1, 'Vous retrouverez toutes les informations relatives à votre événement ci-dessous');
        $this->assertEmailHtmlBodyContains($email2, 'Vous avez reçu un commentaire');

        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('app_events_register_succeeded');
    }

    public function testFullyBookedEvent(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/events/89f72b23-55e9-4975-b640-da24890095b7/register');

        $this->assertSame(0, $crawler->filter('form[data-testid="registration-form"]')->count());
        $this->assertSame('Cet événement est complet.', $crawler->filter('[data-testid="event-full"]')->text());
    }

    public function testEventWithOnlyOneAvailablePlace(): void
    {
        $client = $this->login();
        $crawler1 = $client->request('GET', '/events/a6ed00e5-a566-4115-a547-01378baba9b1/register');
        $crawler2 = $client->request('GET', '/events/a6ed00e5-a566-4115-a547-01378baba9b1/register');

        $saveButton = $crawler1->selectButton('Valider mon inscription');
        $form = $saveButton->form();
        $client->submit($form);

        $crawler2 = $client->request('GET', '/events/a6ed00e5-a566-4115-a547-01378baba9b1/register');
        $saveButton = $crawler2->selectButton('Valider mon inscription');
        $form = $saveButton->form();
        $client->submit($form);

        $this->assertSame('Vous ne pouvez pas vous inscrire à l\'événement. Il se peut que vous soyez déjà inscrit ou qu\'il n\'y ai plus de place disponible.', $crawler2->filter('div.alert--error')->text());
    }

    public function testUnpublishedEvent(): void
    {
        $client = $this->login();
        $client->request('GET', '/events/8d9947e2-02c1-4064-b385-2749b85f1f2d/register');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testEventNotFound(): void
    {
        $client = $this->login();
        $client->request('GET', '/events/54bbf639-5049-4cd5-b0fb-5c4880c17e5f/register');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testBadParameter(): void
    {
        $client = $this->login();
        $client->request('GET', '/events/abc/register');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testWithoutAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/register');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

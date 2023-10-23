<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\App\Profile;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class EditProfileControllerTest extends AbstractWebTestCase
{
    public function testEditProfile(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Modifier mon profil', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Modifier mon profil - Jeunot', $crawler);

        $saveButton = $crawler->selectButton('Enregistrer');
        $form = $saveButton->form();
        $form['profile_form[firstName]'] = 'Mathieu';
        $form['profile_form[lastName]'] = 'Marchois';
        $form['profile_form[email]'] = 'mathieu@fairness.coop';
        $form['profile_form[gender]'] = 'male';
        $form['profile_form[biography]'] = 'Je suis un développeur';
        $form['profile_form[city]'] = 'Paris';
        $form['profile_form[birthday]'] = '1900-01-01';
        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('app_profile_detail');
    }

    public function testEmptyValues(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit');

        $saveButton = $crawler->selectButton('Enregistrer');
        $form = $saveButton->form();
        $form['profile_form[firstName]'] = '';
        $form['profile_form[lastName]'] = '';
        $form['profile_form[email]'] = '';
        $form['profile_form[biography]'] = '';
        $form['profile_form[city]'] = '';
        $form['profile_form[birthday]'] = '';
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#profile_form_lastName_error')->text());
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#profile_form_firstName_error')->text());
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#profile_form_email_error')->text());
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#profile_form_city_error')->text());
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#profile_form_biography_error')->text());
        $this->assertSame('Cette valeur doit être de type date.', $crawler->filter('#profile_form_birthday_error')->text());
    }

    public function testBadValues(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit');

        $saveButton = $crawler->selectButton('Enregistrer');
        $form = $saveButton->form();
        $form['profile_form[firstName]'] = str_repeat('a', 101);
        $form['profile_form[lastName]'] = str_repeat('a', 101);
        $form['profile_form[email]'] = 'mathieu';
        $form['profile_form[biography]'] = str_repeat('a', 256);
        $form['profile_form[city]'] = str_repeat('a', 151);
        $form['profile_form[birthday]'] = 'date';
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Cette chaîne est trop longue. Elle doit avoir au maximum 100 caractères.', $crawler->filter('#profile_form_lastName_error')->text());
        $this->assertSame('Cette chaîne est trop longue. Elle doit avoir au maximum 100 caractères.', $crawler->filter('#profile_form_firstName_error')->text());
        $this->assertSame('Cette chaîne est trop longue. Elle doit avoir au maximum 255 caractères.', $crawler->filter('#profile_form_biography_error')->text());
        $this->assertSame('Cette chaîne est trop longue. Elle doit avoir au maximum 150 caractères.', $crawler->filter('#profile_form_city_error')->text());
        $this->assertSame("Cette valeur n'est pas une adresse email valide.", $crawler->filter('#profile_form_email_error')->text());
        $this->assertSame('Veuillez entrer une date de naissance valide.', $crawler->filter('#profile_form_birthday_error')->text());

        // Email too long
        $form['profile_form[email]'] = str_repeat('a', 101) . '@gmail.com';
        $crawler = $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Cette chaîne est trop longue. Elle doit avoir au maximum 100 caractères.', $crawler->filter('#profile_form_email_error')->text());

        // Age too low
        $form['profile_form[birthday]'] = '2020-01-01';
        $crawler = $client->submit($form);
        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('La mission de Jeunot étant de contribuer au “mieux vieillir”, la plateforme est réservée aux plus de 55 ans.', $crawler->filter('#profile_form_birthday_error')->text());
    }

    public function testEmailAlreadyRegistered(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit');

        $saveButton = $crawler->selectButton('Enregistrer');
        $form = $saveButton->form();
        $form['profile_form[firstName]'] = 'Gregory';
        $form['profile_form[lastName]'] = 'Pelletier';
        $form['profile_form[email]'] = 'gregory.pelletier@fairness.coop';
        $form['profile_form[gender]'] = 'male';
        $form['profile_form[biography]'] = 'Je suis un développeur';
        $form['profile_form[city]'] = 'Paris';
        $form['profile_form[birthday]'] = '1900-01-01';
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Un compte est déjà associé à cette adresse e-mail.', $crawler->filter('#profile_form_email_error')->text());
    }

    public function testWithoutAuthenticatedUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/app/profile/edit');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

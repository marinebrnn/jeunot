<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\App\Profile;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class EditPasswordControllerTest extends AbstractWebTestCase
{
    public function testEditPassword(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit/password');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Modifier mon mot de passe', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Modifier mon mot de passe - Jeunot', $crawler);

        $saveButton = $crawler->selectButton('Changer mon mot de passe');
        $form = $saveButton->form();
        $form['change_password_form[oldPassword]'] = 'password123';
        $form['change_password_form[newPassword][first]'] = 'password123456';
        $form['change_password_form[newPassword][second]'] = 'password123456';

        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('app_profile_detail');
    }

    public function testOldWrongPassword(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit/password');

        $saveButton = $crawler->selectButton('Changer mon mot de passe');
        $form = $saveButton->form();
        $form['change_password_form[oldPassword]'] = 'password123456';
        $form['change_password_form[newPassword][first]'] = 'password123';
        $form['change_password_form[newPassword][second]'] = 'password123';

        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Votre ancien mot de passe ne correspond pas', $crawler->filter('#change_password_form_oldPassword_error')->text());
    }

    public function testEmptyValues(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit/password');

        $saveButton = $crawler->selectButton('Changer mon mot de passe');
        $form = $saveButton->form();
        $form['change_password_form[oldPassword]'] = '';
        $form['change_password_form[newPassword][first]'] = '';
        $form['change_password_form[newPassword][second]'] = '';
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#change_password_form_newPassword_first_error')->text());
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#change_password_form_oldPassword_error')->text());
    }

    public function testBadValues(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/edit/password');

        $saveButton = $crawler->selectButton('Changer mon mot de passe');
        $form = $saveButton->form();

        // Password too short
        $form['change_password_form[oldPassword]'] = 'password';
        $form['change_password_form[newPassword][first]'] = 'password';
        $form['change_password_form[newPassword][second]'] = 'password';
        $crawler = $client->submit($form);
        $this->assertResponseStatusCodeSame(422);

        $this->assertSame('Cette chaîne est trop courte. Elle doit avoir au minimum 10 caractères.', $crawler->filter('#change_password_form_newPassword_first_error')->text());
        $this->assertSame('Cette chaîne est trop courte. Elle doit avoir au minimum 10 caractères.', $crawler->filter('#change_password_form_oldPassword_error')->text());
    }

    public function testWithoutAuthenticatedUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/app/profile/edit/password');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

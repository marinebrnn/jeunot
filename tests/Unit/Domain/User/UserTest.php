<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\Enum\GenderEnum;
use App\Domain\User\Enum\UserRoleEnum;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testGetters(): void
    {
        $birthday = new \DateTime('1989-09-17');
        $birthday2 = new \DateTime('1987-05-22');
        $registration = new \DateTime('2023-10-10');
        $user = new User(
            '9cebe00d-04d8-48da-89b1-059f6b7bfe44',
            'Mathieu',
            'Marchois',
            'mathieu@fairness.coop',
            'password',
            UserRoleEnum::ROLE_ADMIN->value,
            $birthday,
            $registration,
            false,
        );

        $this->assertSame('9cebe00d-04d8-48da-89b1-059f6b7bfe44', $user->getUuid());
        $this->assertSame('Mathieu', $user->getFirstName());
        $this->assertSame('Marchois', $user->getLastName());
        $this->assertSame('mathieu@fairness.coop', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame(UserRoleEnum::ROLE_ADMIN->value, $user->getRole());
        $this->assertSame($birthday, $user->getBirthday());
        $this->assertSame($registration, $user->getRegistrationDate());
        $this->assertEmpty($user->getCity());
        $this->assertEmpty($user->getGender());
        $this->assertEmpty($user->getBiography());
        $this->assertFalse($user->shouldDisplayMyAge());
        $this->assertFalse($user->isVerified());

        $user->verified();
        $this->assertTrue($user->isVerified());

        $user->updatePassword('newPassword');
        $this->assertSame('newPassword', $user->getPassword());

        $user->updateProfile(
            'Hélène',
            'MAITRE-MARCHOIS',
            'helene@fairness.coop',
            'Je suis gérante de la coopérative',
            GenderEnum::FEMALE->value,
            'Paris',
            true,
            $birthday2,
        );
        $this->assertSame('Hélène', $user->getFirstName());
        $this->assertSame('MAITRE-MARCHOIS', $user->getLastName());
        $this->assertSame('helene@fairness.coop', $user->getEmail());
        $this->assertSame($birthday2, $user->getBirthday());
        $this->assertSame('Paris', $user->getCity());
        $this->assertSame(GenderEnum::FEMALE->value, $user->getGender());
        $this->assertSame('Je suis gérante de la coopérative', $user->getBiography());
        $this->assertTrue($user->shouldDisplayMyAge());
    }
}

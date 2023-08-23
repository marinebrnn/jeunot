<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User;

use App\Domain\User\User;
use App\Domain\User\UserRoleEnum;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testGetters(): void
    {
        $birthday = new \DateTime('1989-09-17');
        $user = new User(
            '9cebe00d-04d8-48da-89b1-059f6b7bfe44',
            'Mathieu',
            'Marchois',
            'mathieu@fairness.coop',
            'password',
            UserRoleEnum::ROLE_ADMIN->value,
            $birthday,
            false,
        );

        $this->assertSame('9cebe00d-04d8-48da-89b1-059f6b7bfe44', $user->getUuid());
        $this->assertSame('Mathieu', $user->getFirstName());
        $this->assertSame('Marchois', $user->getLastName());
        $this->assertSame('mathieu@fairness.coop', $user->getEmail());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame(UserRoleEnum::ROLE_ADMIN->value, $user->getRole());
        $this->assertSame($birthday, $user->getBirthday());
        $this->assertFalse($user->isVerified());

        $user->verified();
        $this->assertTrue($user->isVerified());
    }
}

<?php

declare(strict_types=1);

namespace App\Test\Unit\Infrastructure\Security;

use App\Infrastructure\Security\SymfonyUser;
use PHPUnit\Framework\TestCase;

class SymfonyUserTest extends TestCase
{
    public function testUser()
    {
        $user = new SymfonyUser(
            '2d3724f1-2910-48b4-ba56-81796f6e100b',
            'mathieu@fairness.coop',
            'Mathieu',
            'MARCHOIS',
            'password',
            ['ROLE_USER'],
        );

        $this->assertSame('2d3724f1-2910-48b4-ba56-81796f6e100b', $user->getUuid());
        $this->assertSame(['ROLE_USER'], $user->getRoles());
        $this->assertSame('Mathieu', $user->getFirstName());
        $this->assertSame('MARCHOIS', $user->getLastName());
        $this->assertSame(null, $user->getSalt());
        $this->assertSame('mathieu@fairness.coop', $user->getUsername());
        $this->assertSame('mathieu@fairness.coop', $user->getUserIdentifier());
        $this->assertSame('password', $user->getPassword());
        $this->assertEmpty($user->eraseCredentials());
    }
}

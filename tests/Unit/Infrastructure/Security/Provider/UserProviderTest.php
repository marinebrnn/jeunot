<?php

declare(strict_types=1);

namespace App\Test\Unit\Infrastructure\Security;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use App\Domain\User\UserRoleEnum;
use App\Infrastructure\Security\Provider\UserProvider;
use App\Infrastructure\Security\SymfonyUser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserProviderTest extends TestCase
{
    public function testLoadUserByIdentifier()
    {
        $user = $this->createMock(User::class);
        $user
            ->expects(self::once())
            ->method('getFirstName')
            ->willReturn('Mathieu');
        $user
            ->expects(self::once())
            ->method('getLastName')
            ->willReturn('MARCHOIS');
        $user
            ->expects(self::once())
            ->method('getEmail')
            ->willReturn('mathieu@fairness.coop');
        $user
            ->expects(self::once())
            ->method('getPassword')
            ->willReturn('password');
        $user
            ->expects(self::once())
            ->method('getRole')
            ->willReturn(UserRoleEnum::ROLE_ADMIN->value);
        $user
            ->expects(self::once())
            ->method('getUuid')
            ->willReturn('2d3724f1-2910-48b4-ba56-81796f6e100b');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn($user);

        $provider = new UserProvider($userRepository);
        $expectedResult = new SymfonyUser(
            '2d3724f1-2910-48b4-ba56-81796f6e100b',
            'mathieu@fairness.coop',
            'Mathieu',
            'MARCHOIS',
            'password',
            [UserRoleEnum::ROLE_ADMIN->value],
        );

        $this->assertEquals($provider->loadUserByIdentifier('mathieu@fairness.coop'), $expectedResult);
    }

    public function testUserNotFound()
    {
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Unable to find the user mathieu@fairness.coop');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn(null);

        $provider = new UserProvider($userRepository);
        $this->assertEmpty($provider->loadUserByIdentifier('mathieu@fairness.coop'));
    }
}

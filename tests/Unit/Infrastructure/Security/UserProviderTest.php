<?php

declare(strict_types=1);

namespace App\Test\Unit\Infrastructure\Security;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use App\Infrastructure\Security\SymfonyUser;
use App\Infrastructure\Security\UserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class UserProviderTest extends TestCase
{
    public function testLoadUserByIdentifier()
    {
        $user = $this->createMock(User::class);
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn($user);

        $provider = new UserProvider($userRepository);
        $expectedResult = new SymfonyUser($user);

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

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\User\Specification;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\IsUserAlreadyRegistered;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

final class IsUserAlreadyRegisteredTest extends TestCase
{
    public function testUserAlreadyExist(): void
    {
        $user = $this->createMock(User::class);
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn($user);

        $pattern = new IsUserAlreadyRegistered($userRepository);
        $this->assertTrue($pattern->isSatisfiedBy('mathieu@fairness.coop'));
    }

    public function testUserDoesntExist(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn(null);

        $pattern = new IsUserAlreadyRegistered($userRepository);
        $this->assertFalse($pattern->isSatisfiedBy('mathieu@fairness.coop'));
    }
}

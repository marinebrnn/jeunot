<?php

declare(strict_types=1);

namespace App\Test\Unit\Infrastructure\Security;

use App\Infrastructure\Security\SymfonyUser;
use App\Infrastructure\Security\UserChecker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

final class UserCheckerTest extends TestCase
{
    public function testCheckPreAuthUserVerified(): void
    {
        $user = $this->createMock(SymfonyUser::class);
        $user
            ->expects(self::once())
            ->method('isVerified')
            ->willReturn(true);

        $checker = new UserChecker();
        $this->assertEmpty($checker->checkPreAuth($user));
    }

    public function testCheckPreAuthUserNotVerified(): void
    {
        $this->expectException(CustomUserMessageAccountStatusException::class);
        $this->expectExceptionMessage('login.error.not_verified_account');

        $user = $this->createMock(SymfonyUser::class);
        $user
            ->expects(self::once())
            ->method('isVerified')
            ->willReturn(false);

        $checker = new UserChecker();
        $this->assertEmpty($checker->checkPreAuth($user));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\PasswordHasherInterface;
use App\Application\User\Command\ChangePasswordCommand;
use App\Application\User\Command\ChangePasswordCommandHandler;
use App\Domain\User\Exception\PasswordDoesntMatchException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ChangePasswordCommandHandlerTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $passwordHasher;
    private ChangePasswordCommand $command;

    public function setUp(): void
    {
        $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->command = new ChangePasswordCommand('0b507871-8b5e-4575-b297-a630310fc06e');
        $this->command->oldPassword = 'oldPassword';
        $this->command->newPassword = 'newPassword';
    }

    public function testChangePassword(): void
    {
        $user = $this->createMock(User::class);
        $user
            ->expects(self::once())
            ->method('updatePassword')
            ->with('newPasswordHash');
        $user
            ->expects(self::once())
            ->method('getPassword')
            ->willReturn('oldPasswordHash');

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('0b507871-8b5e-4575-b297-a630310fc06e')
            ->willReturn($user);

        $this->passwordHasher
            ->expects(self::once())
            ->method('hash')
            ->with('newPassword')
            ->willReturn('newPasswordHash');

        $this->passwordHasher
            ->expects(self::once())
            ->method('verify')
            ->with('oldPasswordHash', 'oldPassword')
            ->willReturn(true);

        $handler = new ChangePasswordCommandHandler($this->userRepository, $this->passwordHasher);
        $this->assertEquals(($handler)($this->command), $user);
    }

    public function testPasswordNotMatch(): void
    {
        $this->expectException(PasswordDoesntMatchException::class);

        $user = $this->createMock(User::class);
        $user
            ->expects(self::never())
            ->method('updatePassword');
        $user
            ->expects(self::once())
            ->method('getPassword')
            ->willReturn('oldPasswordHash');

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('0b507871-8b5e-4575-b297-a630310fc06e')
            ->willReturn($user);

        $this->passwordHasher
            ->expects(self::never())
            ->method('hash');

        $this->passwordHasher
            ->expects(self::once())
            ->method('verify')
            ->with('oldPasswordHash', 'oldPassword')
            ->willReturn(false);

        $handler = new ChangePasswordCommandHandler($this->userRepository, $this->passwordHasher);
        ($handler)($this->command);
    }

    public function testUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $user = $this->createMock(User::class);
        $user
            ->expects(self::never())
            ->method('updatePassword');
        $user
            ->expects(self::never())
            ->method('getPassword');

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('0b507871-8b5e-4575-b297-a630310fc06e')
            ->willReturn(null);

        $this->passwordHasher
            ->expects(self::never())
            ->method('hash');

        $this->passwordHasher
            ->expects(self::never())
            ->method('verify');

        $handler = new ChangePasswordCommandHandler($this->userRepository, $this->passwordHasher);
        ($handler)($this->command);
    }
}

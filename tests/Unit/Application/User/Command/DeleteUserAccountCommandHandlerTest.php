<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\User\Command\DeleteUserAccountCommand;
use App\Application\User\Command\DeleteUserAccountCommandHandler;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DeleteUserAccountCommandHandlerTest extends TestCase
{
    private MockObject $userRepository;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    }

    public function testSuccessfullyRemoved(): void
    {
        $user = $this->createMock(User::class);
        $this->userRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('0b507871-8b5e-4575-b297-a630310fc06e')
            ->willReturn($user);

        $this->userRepository
            ->expects(self::once())
            ->method('remove')
            ->with($user);

        $handler = new DeleteUserAccountCommandHandler($this->userRepository);
        $command = new DeleteUserAccountCommand('0b507871-8b5e-4575-b297-a630310fc06e');
        ($handler)($command);
    }

    public function testUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByUuid')
            ->with('0b507871-8b5e-4575-b297-a630310fc06e')
            ->willReturn(null);

        $this->userRepository
            ->expects(self::never())
            ->method('remove');

        $handler = new DeleteUserAccountCommandHandler($this->userRepository);
        $command = new DeleteUserAccountCommand('0b507871-8b5e-4575-b297-a630310fc06e');
        ($handler)($command);
    }
}

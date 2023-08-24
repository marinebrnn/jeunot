<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\User\Command\ConfirmAccountCommand;
use App\Application\User\Command\ConfirmAccountCommandHandler;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ConfirmAccountCommandHandlerTest extends TestCase
{
    private MockObject $userRepository;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    }

    public function testUserVerified(): void
    {
        $user = $this->createMock(User::class);
        $user
            ->expects(self::once())
            ->method('verified');

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn($user);

        $command = new ConfirmAccountCommand('  MAthieu@faIrness.coop   ');
        $handler = new ConfirmAccountCommandHandler($this->userRepository);

        ($handler)($command);
    }

    public function testUserNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->userRepository
            ->expects(self::once())
            ->method('findOneByEmail')
            ->with('mathieu@fairness.coop')
            ->willReturn(null);

        $command = new ConfirmAccountCommand('  MAthieu@faIrness.coop   ');
        $handler = new ConfirmAccountCommandHandler($this->userRepository);

        ($handler)($command);
    }
}

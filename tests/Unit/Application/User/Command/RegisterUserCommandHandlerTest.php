<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\IdFactoryInterface;
use App\Application\PasswordHasherInterface;
use App\Application\User\Command\RegisterUserCommand;
use App\Application\User\Command\RegisterUserCommandHandler;
use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\IsUserAlreadyRegistered;
use App\Domain\User\User;
use App\Domain\User\UserRoleEnum;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RegisterUserCommandHandlerTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $passwordHasher;
    private MockObject $idFactory;
    private MockObject $isUserAlreadyRegistered;
    private RegisterUserCommand $command;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $this->idFactory = $this->createMock(IdFactoryInterface::class);
        $this->isUserAlreadyRegistered = $this->createMock(IsUserAlreadyRegistered::class);

        $command = new RegisterUserCommand();
        $command->firstName = 'Mathieu';
        $command->lastName = 'MARCHOIS';
        $command->email = '  matHieu@fAirness.coop   ';
        $command->password = 'password';
        $command->birthday = new \DateTime('1989-09-17');

        $this->command = $command;
    }

    public function testSuccessfullyRegistered(): void
    {
        $this->isUserAlreadyRegistered
            ->expects(self::once())
            ->method('isSatisfiedBy')
            ->with('mathieu@fairness.coop')
            ->willReturn(false);

        $this->idFactory
            ->expects(self::once())
            ->method('make')
            ->willReturn('0b507871-8b5e-4575-b297-a630310fc06e');

        $this->passwordHasher
            ->expects(self::once())
            ->method('hash')
            ->willReturn('hashedPassword');

        $user = new User(
            '0b507871-8b5e-4575-b297-a630310fc06e',
            'Mathieu',
            'MARCHOIS',
            'mathieu@fairness.coop',
            'hashedPassword',
            UserRoleEnum::ROLE_USER->value,
            new \DateTime('1989-09-17'),
            false,
        );

        $this->userRepository
            ->expects(self::once())
            ->method('add')
            ->with($user)
            ->willReturn($user);

        $handler = new RegisterUserCommandHandler(
            $this->userRepository,
            $this->passwordHasher,
            $this->idFactory,
            $this->isUserAlreadyRegistered,
        );

        $this->assertEquals($user, ($handler)($this->command));
    }

    public function testUserAlreadyRegistered(): void
    {
        $this->expectException(UserAlreadyRegisteredException::class);

        $this->isUserAlreadyRegistered
            ->expects(self::once())
            ->method('isSatisfiedBy')
            ->with('mathieu@fairness.coop')
            ->willReturn(true);

        $this->idFactory
            ->expects(self::never())
            ->method('make');

        $this->passwordHasher
            ->expects(self::never())
            ->method('hash');

        $this->userRepository
            ->expects(self::never())
            ->method('add');

        $handler = new RegisterUserCommandHandler(
            $this->userRepository,
            $this->passwordHasher,
            $this->idFactory,
            $this->isUserAlreadyRegistered,
        );

        ($handler)($this->command);
    }
}

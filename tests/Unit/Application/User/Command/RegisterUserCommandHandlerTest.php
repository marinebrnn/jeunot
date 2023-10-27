<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\User\Command;

use App\Application\DateUtilsInterface;
use App\Application\IdFactoryInterface;
use App\Application\PasswordHasherInterface;
use App\Application\User\Command\RegisterUserCommand;
use App\Application\User\Command\RegisterUserCommandHandler;
use App\Domain\User\Enum\UserRoleEnum;
use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\IsUserAlreadyRegistered;
use App\Domain\User\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RegisterUserCommandHandlerTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $passwordHasher;
    private MockObject $idFactory;
    private MockObject $isUserAlreadyRegistered;
    private MockObject $dateUtils;
    private RegisterUserCommand $command;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->passwordHasher = $this->createMock(PasswordHasherInterface::class);
        $this->idFactory = $this->createMock(IdFactoryInterface::class);
        $this->dateUtils = $this->createMock(DateUtilsInterface::class);
        $this->isUserAlreadyRegistered = $this->createMock(IsUserAlreadyRegistered::class);

        $command = new RegisterUserCommand();
        $command->firstName = 'Mathieu';
        $command->lastName = 'MARCHOIS';
        $command->email = '  matHieu@fAirness.coop   ';
        $command->password = 'password';
        $command->birthday = new \DateTime('1989-09-17');
        $command->howYouHeardAboutUs = 'Par les réseaux sociaux';

        $this->command = $command;
    }

    public function testSuccessfullyRegistered(): void
    {
        $registration = new \DateTimeImmutable('2023-10-17');

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

        $this->dateUtils
            ->expects(self::once())
            ->method('getNow')
            ->willReturn($registration);

        $user = new User(
            '0b507871-8b5e-4575-b297-a630310fc06e',
            'Mathieu',
            'MARCHOIS',
            'mathieu@fairness.coop',
            'hashedPassword',
            UserRoleEnum::ROLE_USER->value,
            new \DateTime('1989-09-17'),
            $registration,
            false,
            'Par les réseaux sociaux',
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
            $this->dateUtils,
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

        $this->dateUtils
            ->expects(self::never())
            ->method('getNow');

        $handler = new RegisterUserCommandHandler(
            $this->userRepository,
            $this->passwordHasher,
            $this->idFactory,
            $this->isUserAlreadyRegistered,
            $this->dateUtils,
        );

        ($handler)($this->command);
    }
}

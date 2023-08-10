<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\IdFactoryInterface;
use App\Application\PasswordHasherInterface;
use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\IsUserAlreadyRegistered;
use App\Domain\User\User;
use App\Domain\User\UserRoleEnum;

final class RegisterUserCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly IdFactoryInterface $idFactory,
        private readonly IsUserAlreadyRegistered $isUserAlreadyRegistered,
    ) {
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        $email = trim(strtolower($command->email));

        if ($this->isUserAlreadyRegistered->isSatisfiedBy($email)) {
            throw new UserAlreadyRegisteredException();
        }

        $this->userRepository->add(
            new User(
                uuid: $this->idFactory->make(),
                firstName: $command->firstName,
                lastName: $command->lastName,
                email: $email,
                password: $this->passwordHasher->hash($command->password),
                role: UserRoleEnum::ROLE_USER->value,
                birthday: $command->birthday,
                isVerified: false,
            ),
        );
    }
}

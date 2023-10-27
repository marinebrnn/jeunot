<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\DateUtilsInterface;
use App\Application\IdFactoryInterface;
use App\Application\PasswordHasherInterface;
use App\Domain\User\Enum\UserRoleEnum;
use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\IsUserAlreadyRegistered;
use App\Domain\User\User;

final readonly class RegisterUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private IdFactoryInterface $idFactory,
        private IsUserAlreadyRegistered $isUserAlreadyRegistered,
        private DateUtilsInterface $dateUtils,
    ) {
    }

    public function __invoke(RegisterUserCommand $command): User
    {
        $email = trim(strtolower($command->email));

        if ($this->isUserAlreadyRegistered->isSatisfiedBy($email)) {
            throw new UserAlreadyRegisteredException();
        }

        return $this->userRepository->add(
            new User(
                uuid: $this->idFactory->make(),
                firstName: $command->firstName,
                lastName: $command->lastName,
                email: $email,
                password: $this->passwordHasher->hash($command->password),
                role: UserRoleEnum::ROLE_USER->value,
                birthday: $command->birthday,
                registrationDate: $this->dateUtils->getNow(),
                isVerified: false,
                howYouHeardAboutUs: $command->howYouHeardAboutUs,
            ),
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\PasswordHasherInterface;
use App\Domain\User\Exception\PasswordDoesntMatchException;
use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;

final readonly class ChangePasswordCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(ChangePasswordCommand $command): User
    {
        $user = $this->userRepository->findOneByUuid($command->uuid);
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        if (!$this->passwordHasher->verify($user->getPassword(), $command->oldPassword)) {
            throw new PasswordDoesntMatchException();
        }

        $user->updatePassword($this->passwordHasher->hash($command->newPassword));

        return $user;
    }
}

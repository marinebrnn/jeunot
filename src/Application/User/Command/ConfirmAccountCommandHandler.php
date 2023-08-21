<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;

final class ConfirmAccountCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(ConfirmAccountCommand $command): User
    {
        $email = trim(strtolower($command->email));
        $user = $this->userRepository->findOneByEmail($email);

        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $user->verified();

        return $user;
    }
}

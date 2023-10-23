<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Exception\UserNotFoundException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\User;

final readonly class DeleteUserAccountCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(DeleteUserAccountCommand $command): void
    {
        $user = $this->userRepository->findOneByUuid($command->uuid);
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        $this->userRepository->remove($user);
    }
}

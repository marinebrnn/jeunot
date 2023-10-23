<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Domain\User\Exception\UserAlreadyRegisteredException;
use App\Domain\User\Specification\IsUserAlreadyRegistered;
use App\Domain\User\User;

final readonly class UpdateProfileCommandHandler
{
    public function __construct(
        private IsUserAlreadyRegistered $isUserAlreadyRegistered,
    ) {
    }

    public function __invoke(UpdateProfileCommand $command): User
    {
        $email = trim(strtolower($command->email));
        $user = $command->user;

        if ($email !== $user->getEmail() && $this->isUserAlreadyRegistered->isSatisfiedBy($email)) {
            throw new UserAlreadyRegisteredException();
        }

        $user->updateProfile(
            firstName: $command->firstName,
            lastName: $command->lastName,
            email: $email,
            biography: $command->biography,
            gender: $command->gender,
            city: $command->city,
            displayMyAge: $command->displayMyAge,
            birthday: $command->birthday,
        );

        return $user;
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        /** @var SymfonyUser $user */
        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException('login.error.not_verified_account');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}

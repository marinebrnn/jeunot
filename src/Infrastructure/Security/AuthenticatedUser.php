<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class AuthenticatedUser
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getUser(): User
    {
        /** @var SymfonyUser */
        $user = $this->security->getUser();

        return $this->entityManager->getReference(User::class, $user->getUuid());
    }
}

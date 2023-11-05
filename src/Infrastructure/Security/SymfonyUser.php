<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\User\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class SymfonyUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $uuid;
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $password;
    private ?string $avatar;
    private bool $isVerified;
    private array $roles;

    public function __construct(
        User $user,
    ) {
        $this->uuid = $user->getUuid();
        $this->email = $user->getEmail();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->password = $user->getPassword();
        $this->isVerified = $user->isVerified();
        $this->avatar = $user->getAvatar();
        $this->roles = [$user->getRole()];
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
    }

    public function update(User $user): void
    {
        $this->email = $user->getEmail();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->password = $user->getPassword();
    }
}

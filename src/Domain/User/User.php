<?php

declare(strict_types=1);

namespace App\Domain\User;

class User
{
    private ?string $biography = '';
    private ?string $city = '';
    private ?bool $displayMyAge = false;
    private ?string $gender = '';
    private ?string $avatar = '';

    public function __construct(
        private string $uuid,
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $password,
        private string $role,
        private \DateTimeInterface $birthday,
        private \DateTimeInterface $registrationDate,
        private bool $isVerified = false,
        private ?string $howYouHeardAboutUs = null,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getBirthday(): \DateTimeInterface
    {
        return $this->birthday;
    }

    public function getRegistrationDate(): \DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function getHowYouHeardAboutUs(): ?string
    {
        return $this->howYouHeardAboutUs;
    }

    public function shouldDisplayMyAge(): ?bool
    {
        return $this->displayMyAge;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function verified(): void
    {
        $this->isVerified = true;
    }

    public function updatePassword(string $password): void
    {
        $this->password = $password;
    }

    public function updateAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function updateProfile(
        string $firstName,
        string $lastName,
        string $email,
        string $biography,
        string $gender,
        string $city,
        bool $displayMyAge,
        \DateTimeInterface $birthday,
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->biography = $biography;
        $this->gender = $gender;
        $this->city = $city;
        $this->displayMyAge = $displayMyAge;
        $this->birthday = $birthday;
    }
}

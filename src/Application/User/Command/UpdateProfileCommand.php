<?php

declare(strict_types=1);

namespace App\Application\User\Command;

use App\Application\CommandInterface;
use App\Domain\User\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UpdateProfileCommand implements CommandInterface
{
    public ?UploadedFile $file = null;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $email;
    public ?\DateTimeInterface $birthday;
    public ?string $gender;
    public ?string $city;
    public ?string $biography;
    public ?bool $displayMyAge = false;

    public function __construct(
        public readonly User $user,
    ) {
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->email = $user->getEmail();
        $this->birthday = $user->getBirthday();
        $this->gender = $user->getGender();
        $this->city = $user->getCity();
        $this->biography = $user->getBiography();
        $this->displayMyAge = $user->shouldDisplayMyAge();
    }
}

<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\User\Enum\GenderEnum;
use App\Domain\User\Enum\UserRoleEnum;
use App\Domain\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User(
            '0b507871-8b5e-4575-b297-a630310fc06e',
            'Mathieu',
            'MARCHOIS',
            'mathieu@fairness.coop',
            'password123',
            UserRoleEnum::ROLE_ADMIN->value,
            new \DateTime('1989-09-17'),
            new \DateTime('2023-10-10'),
            true,
            'picture.jpeg',
        );

        $admin->updateProfile(
            'Mathieu',
            'MARCHOIS',
            'mathieu@fairness.coop',
            'Je suis un développeur',
            GenderEnum::MALE->value,
            'Saint Ouen',
            true,
            new \DateTime('1989-09-17'),
        );

        $user = new User(
            'd47badd9-989e-472b-a80e-9df642e93880',
            'Gregory',
            'PELLETIER',
            'gregory.pelletier@fairness.coop',
            'password234',
            UserRoleEnum::ROLE_USER->value,
            new \DateTime('1984-01-01'),
            new \DateTime('2023-09-17'),
            false,
        );
        $user->updateProfile(
            'Gregory',
            'PELLETIER',
            'gregory.pelletier@fairness.coop',
            'Je suis cycliste',
            GenderEnum::MALE->value,
            'Paris',
            false,
            new \DateTime('1984-01-01'),
        );

        $manager->persist($admin);
        $manager->persist($user);
        $manager->flush();

        $this->addReference('admin', $admin);
        $this->addReference('user', $user);
    }
}

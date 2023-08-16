<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\User\User;
use App\Domain\User\UserRoleEnum;
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
            'password1',
            UserRoleEnum::ROLE_ADMIN->value,
            new \DateTime('1989-09-17'),
            true,
        );

        $user = new User(
            'd47badd9-989e-472b-a80e-9df642e93880',
            'Gregory',
            'PELLETIER',
            'gregory.pelletier@fairness.coop',
            'password2',
            UserRoleEnum::ROLE_USER->value,
            new \DateTime('1984-01-01'),
            false,
        );

        $manager->persist($admin);
        $manager->persist($user);
        $manager->flush();

        $this->addReference('admin', $admin);
        $this->addReference('user', $user);
    }
}

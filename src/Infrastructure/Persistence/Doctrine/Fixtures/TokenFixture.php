<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\User\Token;
use App\Domain\User\TokenTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class TokenFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $confirmAccountToken = new Token(
            '7b9ed5b3-cb2e-4f77-88ef-5371a98de677',
            'confirmAccountToken',
            TokenTypeEnum::CONFIRM_ACCOUNT->value,
            $this->getReference('admin'),
            new \DateTime('2023-08-26 09:00:00'),
        );

        $expiredConfirmAccountToken = new Token(
            '217993a8-af4c-40ad-8afc-75d0e9415865',
            'expiredConfirmAccountToken',
            TokenTypeEnum::CONFIRM_ACCOUNT->value,
            $this->getReference('user'),
            new \DateTime('2023-08-20 19:00:00'),
        );

        $manager->persist($confirmAccountToken);
        $manager->persist($expiredConfirmAccountToken);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}

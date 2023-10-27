<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\Post\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class PostFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $post1 = new Post(
            '7576e7dc-2367-48ac-9898-3f8efdc68081',
            $this->getReference('admin'),
        );
        $post1->setTitle('Lancement de la plateforme Jeunot');
        $post1->setSlug('lancement-de-la-plateforme-jeunot');
        $post1->setDescription('Lorem ipsum');
        $post1->setPublicationDate(new \DateTime('2023-09-13 09:00:00'));
        $post1->setPublished(true);

        $post2 = new Post(
            '2b95d06a-bb9e-4dae-9ad2-f7f9134da283',
            $this->getReference('admin'),
        );
        $post2->setTitle('Article en cours de rédaction');
        $post2->setSlug('article-en-cours-de-redaction');
        $post2->setDescription('Lorem ipsum');
        $post2->setPublicationDate(new \DateTime('2023-09-13 09:00:00'));
        $post2->setPublished(false);

        $manager->persist($post1);
        $manager->persist($post2);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}

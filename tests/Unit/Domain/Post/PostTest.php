<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Post;

use App\Domain\Post\Post;
use App\Domain\User\User;
use PHPUnit\Framework\TestCase;

final class PostTest extends TestCase
{
    public function testGetters(): void
    {
        $publicationDate = new \DateTime('2023-08-25 08:00:00');
        $user = $this->createMock(User::class);

        $post = new Post(
            uuid: '9cebe00d-04d8-48da-89b1-059f6b7bfe44',
            owner: $user,
        );
        $post->setTitle('Création de Jeunot');
        $post->setSlug('creation-de-jeunot');
        $post->setDescription('Lorem ipsum dolor sit amet, consectetuer adipiscing elit.');
        $post->setPublicationDate($publicationDate);
        $post->setPublished(true);
        $post->setPicture('/path/to/my/file');

        $this->assertSame('9cebe00d-04d8-48da-89b1-059f6b7bfe44', $post->getUuid());
        $this->assertSame('Création de Jeunot', $post->getTitle());
        $this->assertSame('creation-de-jeunot', $post->getSlug());
        $this->assertSame('Lorem ipsum dolor sit amet, consectetuer adipiscing elit.', $post->getDescription());
        $this->assertSame($publicationDate, $post->getPublicationDate());
        $this->assertSame($user, $post->getOwner());
        $this->assertSame('/path/to/my/file', $post->getPicture());
        $this->assertTrue($post->isPublished());

        // Hack linked to easy admin to avoid deleting an existing image when editing form
        $post->setPicture(null);
        $this->assertSame('/path/to/my/file', $post->getPicture());
    }
}

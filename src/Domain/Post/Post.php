<?php

declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\User\User;

class Post
{
    private string $title;
    private string $slug;
    private string $description;
    private \DateTimeInterface $publicationDate;
    private ?string $picture;
    private bool $published = false;

    public function __construct(
        private string $uuid,
        private User $owner,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getPublicationDate(): \DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): void
    {
        $this->picture = $picture;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }
}

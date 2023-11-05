<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Event
{
    private string $title;
    private string $description;
    private int $initialAvailablePlaces;
    private \DateTimeInterface $startDate;
    private \DateTimeInterface $endDate;
    private ?string $picture;
    private string $location;
    private bool $published = false;
    private Collection $tags;

    public function __construct(
        private string $uuid,
        private User $owner,
    ) {
        $this->tags = new ArrayCollection();
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

    public function getInitialAvailablePlaces(): int
    {
        return $this->initialAvailablePlaces;
    }

    public function setInitialAvailablePlaces(int $initialAvailablePlaces): void
    {
        $this->initialAvailablePlaces = $initialAvailablePlaces;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): void
    {
        // Hack linked to easy admin to avoid deleting an existing image when editing form
        if ($picture) {
            $this->picture = $picture;
        }
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

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }
}

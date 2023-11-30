<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapter;

use App\Application\DateUtilsInterface;

final class DateUtils implements DateUtilsInterface
{
    public function getNow(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }

    public function getCurrentYear(): string
    {
        return (new \DateTimeImmutable('now'))->format('Y');
    }

    public function getDaysInterval(\DateTimeInterface $date1, \DateTimeInterface $date2): int
    {
        return $date1->diff($date2)->d;
    }
}

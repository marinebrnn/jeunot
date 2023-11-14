<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use App\Application\DateUtilsInterface;

final class DateUtilsMock implements DateUtilsInterface
{
    public function getCurrentYear(): string
    {
        return '2023';
    }

    public function getNow(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('2023-08-25');
    }

    public function getDaysInterval(\DateTimeInterface $date1, \DateTimeInterface $date2): int
    {
        return 0;
    }
}

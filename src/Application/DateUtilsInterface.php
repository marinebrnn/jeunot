<?php

declare(strict_types=1);

namespace App\Application;

interface DateUtilsInterface
{
    public function getNow(): \DateTimeImmutable;

    public function getCurrentYear(): string;

    public function getDaysInterval(\DateTimeInterface $date1, \DateTimeInterface $date2): int;
}

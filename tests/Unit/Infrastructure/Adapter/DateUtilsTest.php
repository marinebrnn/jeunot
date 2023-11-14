<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Infrastructure\Adapter\DateUtils;
use PHPUnit\Framework\TestCase;

final class DateUtilsTest extends TestCase
{
    public function testNow(): void
    {
        $dateUtils = new DateUtils();

        $this->assertEquals((new \DateTimeImmutable('now'))->format('Y-m-d'), $dateUtils->getNow()->format('Y-m-d'));
        $this->assertEquals((new \DateTimeImmutable('now'))->format('Y'), $dateUtils->getCurrentYear());
    }

    public function testDaysInterval(): void
    {
        $dateUtils = new DateUtils();
        $this->assertEquals(0, $dateUtils->getDaysInterval(new \DateTime('2023-11-30'), new \DateTime('2023-11-30')));
        $this->assertEquals(5, $dateUtils->getDaysInterval(new \DateTime('2023-11-25'), new \DateTime('2023-11-30')));
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Symfony\EventSubscriber;

use App\Infrastructure\Symfony\EventSubscriber\LoggedUserSubscriber;
use PHPUnit\Framework\TestCase;

class LoggedUserSubscriberTest extends TestCase
{
    public function testSubscribedEvents(): void
    {
        $this->assertEquals([
            'kernel.request' => [
                ['onKernelRequest', 0],
            ],
        ], LoggedUserSubscriber::getSubscribedEvents());
    }
}

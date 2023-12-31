<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Infrastructure\Adapter\Storage;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\TestCase;

final class StorageTest extends TestCase
{
    public function testDelete(): void
    {
        $filesystemOperator = $this->createMock(FilesystemOperator::class);
        $filesystemOperator
            ->expects(self::once())
            ->method('has')
            ->with('picture.jpeg')
            ->willReturn(true);
        $filesystemOperator
            ->expects(self::once())
            ->method('delete')
            ->with('picture.jpeg');

        $storage = new Storage($filesystemOperator);
        $storage->delete('picture.jpeg');
    }

    public function testCantDelete(): void
    {
        $filesystemOperator = $this->createMock(FilesystemOperator::class);
        $filesystemOperator
            ->expects(self::once())
            ->method('has')
            ->with('picture.jpeg')
            ->willReturn(false);
        $filesystemOperator
            ->expects(self::never())
            ->method('delete');

        $storage = new Storage($filesystemOperator);
        $storage->delete('picture.jpeg');
    }
}

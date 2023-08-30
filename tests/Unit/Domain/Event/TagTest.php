<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Event;

use App\Domain\Event\Tag;
use PHPUnit\Framework\TestCase;

final class TagTest extends TestCase
{
    public function testGetters(): void
    {
        $tag = new Tag('9cebe00d-04d8-48da-89b1-059f6b7bfe44');
        $tag->setTitle('En plein air');

        $this->assertSame('9cebe00d-04d8-48da-89b1-059f6b7bfe44', $tag->getUuid());
        $this->assertSame('En plein air', $tag->getTitle());
    }
}

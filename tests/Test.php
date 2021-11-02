<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testBob(): void
    {
        $this->assertSame(1, 1, 'hello bob');
    }
}
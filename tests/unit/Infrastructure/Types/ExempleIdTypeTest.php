<?php

namespace DoctrineTestingTools\Tests\Infrastructure\Types;

use DoctrineTestingTools\Infrastructure\Types\ExempleIdType;
use PHPUnit\Framework\TestCase;

class ExempleIdTypeTest extends TestCase
{
    public function testName(): void
    {
        $this->assertEquals(ExempleIdType::TYPE_NAME, (new ExempleIdType())->getName());
    }
}

<?php

namespace DoctrineTestingTools\Tests\Domain;

use DoctrineTestingTools\Domain\ExempleId;
use PHPUnit\Framework\TestCase;

class ExempleIdTest extends TestCase
{
    public function testCreation(): void
    {
        $exempleId = new ExempleId();
        $this->assertIsString($exempleId->getId());
        $this->assertNotEmpty($exempleId->getId());
    }

    public function testManualCreation(): void
    {
        $exempleId = new ExempleId("my_id");
        $this->assertEquals("my_id", $exempleId->getId());
    }

    public function testUniqueness(): void
    {
        $id1 = new ExempleId();
        $id2 = new ExempleId();
        $this->assertNotEquals($id1->getId(), $id2->getId());
    }

    public function testComparaison(): void
    {
        $id1 = new ExempleId("similar");
        $id2 = new ExempleId("similar");
        $id3 = new ExempleId("different");

        $this->assertTrue($id1->equals($id2));
        $this->assertFalse($id1->equals($id3));
    }

    public function testToString(): void
    {
        $id = new ExempleId("stringCast");
        $this->assertEquals("stringCast", (string) $id);
    }
}

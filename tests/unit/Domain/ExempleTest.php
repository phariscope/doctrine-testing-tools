<?php

namespace DoctrineTestingTools\Tests\Domain;

use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use PHPUnit\Framework\TestCase;

class ExempleTest extends TestCase
{
    public function testCreation(): void
    {
        $exemple = new Exemple();
        $exempleId = $exemple->getExempleId();
        $this->assertIsString($exempleId->getId());
        $this->assertNotEmpty($exempleId->getId());

        $identitifedExemple = new Exemple(new ExempleId("identity"));
        $exempleId = $identitifedExemple->getExempleId();
        $this->assertEquals("identity", $exempleId->getId());
    }
}

<?php

namespace DoctrineTestingTools\Infrastructure\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use DoctrineTestingTools\Domain\ExempleId;

class ExempleIdType extends Type
{
    public const TYPE_NAME = "exemple_id";

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    /** @param ExempleId $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->getId();
    }

    /** @param string $value */
    public function convertToPHPValue($value, AbstractPlatform $platform): ExempleId
    {
        return new ExempleId($value);
    }

    /** @param array<mixed> $column */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }
}

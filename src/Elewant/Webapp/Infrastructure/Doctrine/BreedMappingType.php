<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Doctrine;

/**
 * @todo Is it ok to use Herding\DomainModel here?
 */

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;

final class BreedMappingType extends Type
{
    const NAME = 'breed';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Breed
    {
        if ($value === null) {
            return null;
        }

        try {
            return Breed::fromString($value);
        } catch (SorryThatIsAnInvalid $exception) {
            throw ConversionException::conversionFailed(
                $value,
                $this->getName()
            );
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof Breed) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [Breed::class]);
        }

        return $value->toString();
    }

    /**
     * @inheritdoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

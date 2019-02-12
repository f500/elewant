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
    private const NAME = 'breed';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param mixed[] $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Breed|null
     * @throws ConversionException
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
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
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string|null
     * @throws ConversionException
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
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
     * @param AbstractPlatform $platform
     * @return bool
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}

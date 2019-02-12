<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel;

use Exception;

final class SorryThatIsAnInvalid extends Exception
{
    public static function shepherdId(string $shepherdId): self
    {
        return new self(sprintf('Sorry, %s is an invalid ShepherdId', $shepherdId));
    }

    public static function herdId(string $herdId): self
    {
        return new self(sprintf('Sorry, %s is an invalid HerdId', $herdId));
    }

    public static function elePHPantId(string $elePHPantId): self
    {
        return new self(sprintf('Sorry, %s is an invalid ElePHPantId', $elePHPantId));
    }

    public static function breed(string $breed): self
    {
        return new self(sprintf('Sorry, %s is an invalid Breed', $breed));
    }
}

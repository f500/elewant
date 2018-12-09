<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel;

use Exception;

final class SorryIDoNotKnowThat extends Exception
{
    public static function event($aggregate, $class): self
    {
        return new self(
            sprintf("Sorry, I (%s) don't know how to apply %s ", get_class($aggregate), get_class($class))
        );
    }
}

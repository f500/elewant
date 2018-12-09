<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel;

use Elewant\Herding\DomainModel\Herd\Herd;
use Exception;

final class SorryICanNotChangeHerd extends Exception
{
    public static function becauseItWasAbandoned(Herd $herd): self
    {
        return new self(
            sprintf(
                'Sorry, herd %s can not be changed because it was abandoned',
                $herd->herdId()->toString()
            )
        );
    }
}

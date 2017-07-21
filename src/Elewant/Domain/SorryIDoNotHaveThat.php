<?php

namespace Elewant\Domain;

use Exception;

final class SorryIDoNotHaveThat extends Exception
{
    public static function typeOfElePHPant(Herd $herd, $elePHPantType)
    {
        return new self(sprintf(
                'Sorry, herd %s does not have any %s elePHPants',
                $herd->herdId()->toString(),
                $elePHPantType)
        );
    }
}

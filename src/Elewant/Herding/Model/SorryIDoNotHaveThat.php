<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

use Exception;

final class SorryIDoNotHaveThat extends Exception
{
    public static function typeOfElePHPant(Herd $herd, Breed $breed)
    {
        return new self(
            sprintf(
                'Sorry, herd %s does not have any %s ElePHPants',
                $herd->herdId()->toString(),
                $breed->toString()
            )
        );
    }

    public static function herd(HerdId $herdId)
    {
        return new self(
            sprintf(
                'Sorry, herdCollection does not have a herd with id %s',
                $herdId->toString()
            )
        );
    }
}

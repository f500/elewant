<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Herd\Herd;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Exception;

final class SorryIDoNotHaveThat extends Exception
{
    public static function typeOfElePHPant(Herd $herd, Breed $breed): self
    {
        return new self(
            sprintf(
                'Sorry, herd %s does not have any %s ElePHPants',
                $herd->herdId()->toString(),
                $breed->toString()
            )
        );
    }

    public static function herd(HerdId $herdId): self
    {
        return new self(
            sprintf(
                'Sorry, herdCollection does not have a herd with id %s',
                $herdId->toString()
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

use Exception;

final class SorryICanNotChangeHerd extends Exception
{
    public static function becauseItWasAbandoned(Herd $herd)
    {
        return new self(sprintf(
                'Sorry, herd %s can not be changed because it was abandoned',
                $herd->herdId()->toString()
        ));
    }
}

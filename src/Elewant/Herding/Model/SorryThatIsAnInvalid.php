<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

final class SorryThatIsAnInvalid extends \Exception
{

    public static function breed($breed)
    {
        return new self(sprintf('Sorry, %s is an invalid Breed', $breed));
    }
}

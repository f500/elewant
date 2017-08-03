<?php

namespace Elewant\Herding\Model;

final class SorryThatIsAnInvalid extends \Exception
{

    public static function breed($breed)
    {
        return new self(sprintf('Sorry, %s is an invalid Breed',$breed));
    }
}

<?php

declare(strict_types=1);

namespace Elewant\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElewantUserBundle extends Bundle
{
    public function getParent() : string
    {
        return 'HWIOAuthBundle';
    }
}

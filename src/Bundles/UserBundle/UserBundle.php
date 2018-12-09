<?php

declare(strict_types=1);

namespace Bundles\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class UserBundle extends Bundle
{
    public function getParent(): string
    {
        return 'HWIOAuthBundle';
    }
}

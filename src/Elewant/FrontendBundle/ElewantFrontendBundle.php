<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle;

use Doctrine\DBAL\Types\Type;
use Elewant\FrontendBundle\Entity\CustomType\Breed;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElewantFrontendBundle extends Bundle
{

    public function boot()
    {
        /**
         * This is probably not the place to put a custom Doctrine type...
         * (no need to look at git blame: it was me: @f_u_e_n_t_e)
         */
        if (!Type::hasType('breed')) {
            Type::addType('breed', Breed::class);
        }
    }
}

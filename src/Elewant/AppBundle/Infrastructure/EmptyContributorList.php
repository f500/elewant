<?php

namespace Elewant\AppBundle\Infrastructure;

use Elewant\AppBundle\Service\ContributorList;

class EmptyContributorList implements ContributorList
{
    public function allContributors() : array
    {
        return [];
    }
}

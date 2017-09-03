<?php

namespace Elewant\AppBundle\Service;

interface ContributorList
{
    /**
     * @return Contributor[]
     */
    public function allContributors(): array;
}

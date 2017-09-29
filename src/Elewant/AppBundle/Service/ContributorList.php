<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Service;

interface ContributorList
{
    /**
     * @return Contributor[]
     */
    public function allContributors(): array;
}

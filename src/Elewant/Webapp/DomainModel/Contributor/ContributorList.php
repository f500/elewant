<?php

declare(strict_types=1);

namespace Elewant\Webapp\DomainModel\Contributor;

interface ContributorList
{
    /**
     * @return Contributor[]
     */
    public function allContributors(): array;
}

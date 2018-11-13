<?php

declare(strict_types=1);

namespace Elewant\Webapp\DomainModel\Herding;

/**
 * Herding ids are okay to use!
 */

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Elewant\Herding\DomainModel\ShepherdId;

interface HerdRepository
{
    /**
     * @param int $limit
     *
     * @return Herd[]
     */
    public function newestHerds(int $limit): array;

    /**
     * @param string $searchString
     *
     * @return Herd[]
     */
    public function search(string $searchString): array;

    /**
     * @param ShepherdId $shepherdId
     *
     * @return Herd|null
     * @throws NonUniqueResultException
     */
    public function findOneByShepherdId(ShepherdId $shepherdId): ?Herd;

    /**
     * @todo: Remove this, it's only used in DoctrineHerdingStatisticsCalculator
     * @todo: in another BC. Also, we shouldn't use Criteria & Collection directly.
     *
     * @param Criteria $criteria
     *
     * @return Collection
     */
    public function matching(Criteria $criteria): Collection;
}

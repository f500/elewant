<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Doctrine;

/**
 * Herding ids are okay to use!
 */

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Webapp\DomainModel\Herding\Herd;
use Elewant\Webapp\DomainModel\Herding\HerdRepository;

final class DoctrineHerdRepository implements HerdRepository
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param int $limit
     * @return Herd[]
     */
    public function newestHerds(int $limit): array
    {
        $dql = <<<EOQ
SELECT h as herd, u.username
FROM Herding:Herd h
JOIN UserBundle:User u WITH h.shepherdId = u.shepherdId
ORDER BY h.formedOn DESC
EOQ;

        $query = $this->getManager()->createQuery($dql);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    /**
     * @param string $searchString
     * @return mixed[]
     */
    public function search(string $searchString): array
    {
        $dql = <<<EOQ
SELECT h.name, u.username
FROM Herding:Herd h
JOIN UserBundle:User u WITH h.shepherdId = u.shepherdId
WHERE 
  h.name LIKE :searchString 
  OR u.username LIKE :searchString
ORDER BY h.formedOn DESC
EOQ;

        $query = $this->getManager()->createQuery($dql);
        $query->setParameter('searchString', '%' . $searchString . '%');

        return $query->getResult();
    }

    /**
     * @param ShepherdId $shepherdId
     * @return Herd|null
     * @throws NonUniqueResultException
     */
    public function findOneByShepherdId(ShepherdId $shepherdId): ?Herd
    {
        $dql = <<<EOQ
SELECT h
FROM Herding:Herd h
WHERE h.shepherdId = :shepherdId
EOQ;

        $query = $this->getManager()->createQuery($dql);
        $query->setParameter('shepherdId', $shepherdId->toString());

        return $query->getOneOrNullResult();
    }

    private function getManager(): EntityManager
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->registry->getManager();

        return $entityManager;
    }

    /**
     * @todo: Remove this, it's only used in DoctrineHerdingStatisticsCalculator
     * @todo: in another BC. Also, we shouldn't use Criteria directly.
     * @param Criteria $criteria
     * @return Collection
     */
    public function matching(Criteria $criteria): Collection
    {
        /** @var EntityRepository $repository */
        $repository = $this->getManager()->getRepository(Herd::class);

        return $repository->matching($criteria);
    }
}

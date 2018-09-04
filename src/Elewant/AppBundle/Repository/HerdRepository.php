<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Elewant\AppBundle\Entity\Herd;
use Elewant\Herding\Model\ShepherdId;

final class HerdRepository extends ServiceEntityRepository
{

    /**
     * HerdRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Herd::class);
    }

    /**
     * @param int $limit
     *
     * @return Herd[]
     */
    public function newestHerds(int $limit): array
    {
        $dql   = <<<EOQ
SELECT h as herd, u.username
FROM ElewantAppBundle:Herd h
JOIN ElewantUserBundle:User u WITH h.shepherdId = u.shepherdId
ORDER BY h.formedOn DESC
EOQ;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($limit);

        $herdsAndUsers = $query->getResult();

        return $herdsAndUsers;
    }

    /**
     * @param string $searchString
     *
     * @return Herd[]
     */
    public function search(string $searchString): array
    {
        $dql = <<<EOQ
SELECT h.name, u.username
FROM ElewantAppBundle:Herd h
JOIN ElewantUserBundle:User u WITH h.shepherdId = u.shepherdId
WHERE 
  h.name LIKE :searchString 
  OR u.username LIKE :searchString
ORDER BY h.formedOn DESC
EOQ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('searchString', '%' . $searchString . '%');

        return $query->getResult();
    }

    public function findOneByShepherdId(ShepherdId $shepherdId): ?Herd
    {
        $dql   = <<<EOQ
SELECT h
FROM ElewantAppBundle:Herd h
WHERE h.shepherdId = :shepherdId
EOQ;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('shepherdId', $shepherdId->toString());

        return $query->getOneOrNullResult();
    }
}

<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Elewant\AppBundle\Entity\ElePHPant;
use Elewant\AppBundle\Entity\Herd;
use Elewant\Herding\Model\ShepherdId;

final class HerdRepository extends EntityRepository
{

    /**
     * @param int $limit
     *
     * @return Herd[]
     */
    public function lastNewHerds(int $limit) : array
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
     * @param int $limit
     *
     * @return ElePHPant[]
     */
    public function lastNewElePHPants(int $limit) : array
    {
        $dql = <<<EOQ
SELECT e
FROM ElewantAppBundle:ElePHPant e
ORDER BY e.adoptedOn DESC
EOQ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    /**
     * @param string $searchString
     *
     * @return Herd[]
     */
    public function search(string $searchString) : array
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

    public function findOneByShepherdId(ShepherdId $shepherdId) :? Herd
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

    public function findOneByName(string $name) :? Herd
    {
        $dql   = <<<EOQ
SELECT h
FROM ElewantAppBundle:Herd h
WHERE h.name = :name
EOQ;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('name', $name);

        return $query->getOneOrNullResult();
    }

}

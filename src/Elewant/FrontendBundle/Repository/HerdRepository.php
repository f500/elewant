<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Elewant\FrontendBundle\Entity\Elephpant;
use Elewant\FrontendBundle\Entity\Herd;

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
SELECT h
FROM ElewantFrontendBundle:Herd h
ORDER BY h.formedOn DESC
EOQ;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    /**
     * @param int $limit
     *
     * @return Elephpant[]
     */
    public function lastNewElePHPants(int $limit) : array
    {
        $dql = <<<EOQ
SELECT e
FROM ElewantFrontendBundle:Elephpant e
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
    public function search(string $searchString): array
    {
        $dql = <<<EOQ
SELECT h
FROM ElewantFrontendBundle:Herd h
WHERE h.name LIKE :searchString
ORDER BY h.formedOn DESC
EOQ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('searchString', '%' . $searchString . '%');

        return $query->getResult();
    }

}

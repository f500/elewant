<?php

declare(strict_types=1);

namespace Bundles\UserBundle\Repository;

use Bundles\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

final class UserRepository extends EntityRepository
{
    /**
     * @param string $username
     *
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findUserByUsername(string $username): ?User
    {
        $dql = <<<EOQ
SELECT u
FROM UserBundle:User u
LEFT JOIN u.connections c
WHERE u.username = :username
EOQ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('username', $username);

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function findUserByResource(string $resource, string $id): ?User
    {
        $dql = <<<EOQ
SELECT u
FROM UserBundle:User u
LEFT JOIN u.connections c
WHERE c.resource = :resource AND c.resourceId = :resourceId
EOQ;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(
            [
                'resource'   => $resource,
                'resourceId' => $id,
            ]
        );

        return $query->getOneOrNullResult();
    }
}

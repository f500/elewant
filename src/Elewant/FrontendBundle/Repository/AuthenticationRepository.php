<?php

namespace Elewant\FrontendBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Elewant\FrontendBundle\Entity\User;

final class AuthenticationRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     */
    public function saveUser(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param string  $resource
     * @param integer $id
     *
     * @return User|null
     */
    public function findUserByResource($resource, $id)
    {
        $dql = <<<EOQ
SELECT
    u
FROM
    ElewantFrontendBundle:User u
    LEFT JOIN ElewantFrontendBundle:UserConnect c
WHERE
    c.resource = :resource
    AND c.resourceId = :resourceId
EOQ;

        $query = $this->entityManager->createQuery($dql);
        $query->setParameters(
            [
                'resource'   => $resource,
                'resourceId' => $id,
            ]
        );

        return $query->getOneOrNullResult();
    }

    /**
     * @return User|null
     */
    public function findUserById($id)
    {
        $dql = <<<EOQ
SELECT
    u
FROM
    ElewantFrontendBundle:User u
    LEFT JOIN ElewantFrontendBundle:UserConnect c
WHERE
    u.id = :id
EOQ;

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter('id', $id);

        $user = $query->getOneOrNullResult();

        return $user;
    }

    /**
     * Finds entities by a set of criteria.
     *
     * This function is required to make the entity unique check works.
     * User:username
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $persister = $this->entityManager->getUnitOfWork()->getEntityPersister(User::class);

        return $persister->loadAll($criteria, $orderBy, $limit, $offset);
    }
}

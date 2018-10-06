<?php

declare(strict_types=1);

namespace Elewant\Herding\Projections;

use Doctrine\DBAL\Connection;

/**
 * Class HerdListing
 *
 * This class is used for simple queries against the herd projection, in order to test the outcome
 * of a command in end-to-end tests. It should _not_ be used in the AppBundle anywhere else.
 */
final class HerdListing
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        return $this->connection->fetchAll(sprintf('SELECT * FROM %s', HerdReadModel::TABLE_HERD));
    }

    public function findById($herdId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_HERD)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        return $qb->execute()->fetch();
    }

    public function findElePHPantsByHerdId($herdId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_ELEPHPANT)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        return $qb->execute()->fetchAll();
    }

    public function findElePHPantByElePHPantId($elePHPantId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_ELEPHPANT)
            ->where('elephpant_id = :elephpantId')
            ->setParameter('elephpantId', $elePHPantId);

        return $qb->execute()->fetch();
    }

    public function findDesiredBreedsByHerdId($herdId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_DESIRED_BREEDS)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        return $qb->execute()->fetchAll();
    }
}

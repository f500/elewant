<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\ProophProjections;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;

/**
 * Class HerdListing
 * This class is used for simple queries against the herd projection, in order to test the outcome
 * of a command in end-to-end tests. It should _not_ be used _anywhere_ else.
 */
final class HerdListing
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return mixed[]
     */
    public function findAll(): array
    {
        return $this->connection->fetchAll(sprintf('SELECT * FROM %s', HerdReadModel::TABLE_HERD));
    }

    /**
     * @param string $herdId
     * @return mixed[]|null
     */
    public function findById(string $herdId): ?array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_HERD)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        /** @var Statement $stmt */
        $stmt = $qb->execute();

        return $stmt->fetch() ?: null;
    }

    /**
     * @param string $herdId
     * @return mixed[]
     */
    public function findElePHPantsByHerdId(string $herdId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_ELEPHPANT)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        /** @var Statement $stmt */
        $stmt = $qb->execute();

        return $stmt->fetchAll();
    }

    /**
     * @param string $elePHPantId
     * @return mixed[]|null
     */
    public function findElePHPantByElePHPantId(string $elePHPantId): ?array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_ELEPHPANT)
            ->where('elephpant_id = :elephpantId')
            ->setParameter('elephpantId', $elePHPantId);

        /** @var Statement $stmt */
        $stmt = $qb->execute();

        return $stmt->fetch() ?: null;
    }

    /**
     * @param string $herdId
     * @return mixed[]
     */
    public function findDesiredBreedsByHerdId(string $herdId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(HerdReadModel::TABLE_DESIRED_BREEDS)
            ->where('herd_id = :herdId')
            ->setParameter('herdId', $herdId);

        /** @var Statement $stmt */
        $stmt = $qb->execute();

        return $stmt->fetchAll();
    }
}

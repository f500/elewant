<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\ProophProjections;

/**
 * @todo Is it ok to use Herding\DomainModel here?
 */

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPantId;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use Prooph\EventStore\Projection\AbstractReadModel;

final class HerdReadModel extends AbstractReadModel
{
    public const TABLE_HERD = 'herd';
    public const TABLE_ELEPHPANT = 'elephpant';
    public const TABLE_DESIRED_BREEDS = 'desired_breed';

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function init(): void
    {
        // Created by a migration.
    }

    public function isInitialized(): bool
    {
        // Created by a migration.
        return true;
    }

    /**
     * @throws DBALException
     */
    public function reset(): void
    {
        $platform = $this->connection->getDatabasePlatform();

        $this->connection->query('SET FOREIGN_KEY_CHECKS=0');
        $this->connection->executeUpdate($platform->getTruncateTableSQL(self::TABLE_HERD));
        $this->connection->executeUpdate($platform->getTruncateTableSQL(self::TABLE_ELEPHPANT));
        $this->connection->executeUpdate($platform->getTruncateTableSQL(self::TABLE_DESIRED_BREEDS));
        $this->connection->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function delete(): void
    {
        // let's not. (Created by a migration)
    }

    /**
     * @param HerdId $herdId
     * @param ShepherdId $shepherdId
     * @param string $name
     * @param DateTimeImmutable $formedOn
     * @throws DBALException
     */
    public function onHerdWasFormed(
        HerdId $herdId,
        ShepherdId $shepherdId,
        string $name,
        DateTimeImmutable $formedOn
    ): void
    {
        $this->connection->insert(
            self::TABLE_HERD,
            [
                'herd_id' => $herdId->toString(),
                'shepherd_id' => $shepherdId->toString(),
                'name' => $name,
                'formed_on' => $formedOn->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @param HerdId $herdId
     * @param string $newHerdName
     * @throws DBALException
     */
    public function onHerdWasRenamed(HerdId $herdId, string $newHerdName): void
    {
        $this->connection->update(
            self::TABLE_HERD,
            [
                'name' => $newHerdName,
            ],
            [
                'herd_id' => $herdId->toString(),
            ]
        );
    }

    /**
     * @param ElePHPantId $elePHPantId
     * @param HerdId $herdId
     * @param Breed $breed
     * @param DateTimeImmutable $adoptedOn
     * @throws DBALException
     */
    public function onElePHPantWasAdoptedByHerd(
        ElePHPantId $elePHPantId,
        HerdId $herdId,
        Breed $breed,
        DateTimeImmutable $adoptedOn
    ): void
    {
        $this->connection->insert(
            self::TABLE_ELEPHPANT,
            [
                'elephpant_id' => $elePHPantId->toString(),
                'herd_id' => $herdId->toString(),
                'breed' => $breed->toString(),
                'adopted_on' => $adoptedOn->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @param ElePHPantId $elePHPantId
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function onElePHPantWasAbandonedByHerd(ElePHPantId $elePHPantId): void
    {
        $this->connection->delete(
            self::TABLE_ELEPHPANT,
            [
                'elephpant_id' => $elePHPantId->toString(),
            ]
        );
    }

    /**
     * @param HerdId $herdId
     * @param Breed $breed
     * @param DateTimeImmutable $desiredOn
     * @throws DBALException
     */
    public function onBreedWasDesiredByHerd(HerdId $herdId, Breed $breed, DateTimeImmutable $desiredOn): void
    {
        try {
            $this->connection->insert(
                self::TABLE_DESIRED_BREEDS,
                [
                    'herd_id' => $herdId->toString(),
                    'breed' => $breed->toString(),
                    'desired_on' => $desiredOn->format('Y-m-d H:i:s'),
                ]
            );
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (UniqueConstraintViolationException $exception) {
            // There are duplicates in the historic data
        }
    }

    /**
     * @param HerdId $herdId
     * @param Breed $breed
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function onBreedDesireWasEliminatedByHerd(HerdId $herdId, Breed $breed): void
    {
        $this->connection->delete(
            self::TABLE_DESIRED_BREEDS,
            [
                'herd_id' => $herdId->toString(),
                'breed' => $breed->toString(),
            ]
        );
    }

    /**
     * @param HerdId $herdId
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function onHerdWasAbandoned(HerdId $herdId): void
    {
        $this->connection->delete(
            self::TABLE_ELEPHPANT,
            [
                'herd_id' => $herdId->toString(),
            ]
        );

        $this->connection->delete(
            self::TABLE_HERD,
            [
                'herd_id' => $herdId->toString(),
            ]
        );
    }
}

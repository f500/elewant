<?php

declare(strict_types=1);

namespace Elewant\Herding\Projections;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\ElePHPantId;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Prooph\EventStore\Projection\AbstractReadModel;

final class HerdReadModel extends AbstractReadModel
{
    const TABLE_HERD           = 'herd';
    const TABLE_ELEPHPANT      = 'elephpant';
    const TABLE_DESIRED_BREEDS = 'desired_breed';

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
     * @throws DBALException
     */
    public function onHerdWasFormed(HerdId $herdId, ShepherdId $shepherdId, string $name, \DateTimeImmutable $formedOn)
    {
        $this->connection->insert(
            self::TABLE_HERD,
            [
                'herd_id'     => $herdId->toString(),
                'shepherd_id' => $shepherdId->toString(),
                'name'        => $name,
                'formed_on'   => $formedOn->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    public function onHerdWasRenamed(HerdId $herdId, string $newHerdName)
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
     * @throws DBALException
     */
    public function onElePHPantWasAdoptedByHerd(ElePHPantId $elePHPantId, HerdId $herdId, Breed $breed, \DateTimeImmutable $adoptedOn)
    {
        $this->connection->insert(
            self::TABLE_ELEPHPANT,
            [
                'elephpant_id' => $elePHPantId->toString(),
                'herd_id'      => $herdId->toString(),
                'breed'        => $breed->toString(),
                'adopted_on'   => $adoptedOn->format('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    public function onElePHPantWasAbandonedByHerd(ElePHPantId $elePHPantId)
    {
        $this->connection->delete(
            self::TABLE_ELEPHPANT,
            [
                'elephpant_id' => $elePHPantId->toString(),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    public function onBreedWasDesiredByHerd(HerdId $herdId, Breed $breed, \DateTimeImmutable $desiredOn)
    {
        try {
            $this->connection->insert(
                self::TABLE_DESIRED_BREEDS,
                [
                    'herd_id'    => $herdId->toString(),
                    'breed'      => $breed->toString(),
                    'desired_on' => $desiredOn->format('Y-m-d H:i:s'),
                ]
            );
        } catch (\Exception $e) {
            $e;
        }
    }

    /**
     * @throws DBALException
     */
    public function onBreedDesireWasEliminatedByHerd(HerdId $herdId, Breed $breed)
    {
        $this->connection->delete(
            self::TABLE_DESIRED_BREEDS,
            [
                'herd_id' => $herdId->toString(),
                'breed'   => $breed->toString(),
            ]
        );
    }

    /**
     * @throws DBALException
     */
    public function onHerdWasAbandoned(HerdId $herdId)
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

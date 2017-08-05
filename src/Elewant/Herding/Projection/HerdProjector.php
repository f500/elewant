<?php

namespace Prooph\ProophessorDo\Projection\User;

use Doctrine\DBAL\Connection;
use Elewant\Herding\Model\Events\ElePHPantWasAbandonedByHerd;
use Elewant\Herding\Model\Events\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\Model\Events\HerdWasFormed;

final class UserProjector
{
    const TABLE_HERD      = 'herd';
    const TABLE_ELEPHPANT = 'elephpant';

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function onHerdWasFormed(HerdWasFormed $event)
    {
        $this->connection->insert(
            self::TABLE_HERD,
            [
                'herd_id' => $event->herdId()->toString(),
                'shepherd_id' => $event->shepherdId()->toString(),
                'name' => $event->name(),
            ]
        );
    }

    public function onElePHPantWasAdoptedByHerd(ElePHPantWasAdoptedByHerd $event)
    {
        $this->connection->insert(
            self::TABLE_ELEPHPANT,
            [
                'elephpant_id' => $event->elePHPantId()->toString(),
                'herd_id' => $event->herdId()->toString(),
                'breed' => $event->breed()->toString(),
            ]
        );
    }

    public function onElePHPantWasAbandonedByHerd(ElePHPantWasAbandonedByHerd $event)
    {
        $this->connection->delete(
            self::TABLE_ELEPHPANT,
            [
                'elephpant_id' => $event->elePHPantId()->toString(),
            ]
        );
    }
}

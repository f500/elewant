<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Elewant\Herding\Projections\HerdProjector;

class Version20170805082923 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $herd = $schema->createTable(HerdProjector::TABLE_HERD);
        $herd->addColumn('herd_id', 'guid');
        $herd->addColumn('shepherd_id', 'guid');
        $herd->addColumn('name', 'string', ['length' => 64]);
        $herd->addColumn('formed_on', 'datetime');
        $herd->setPrimaryKey(['herd_id']);
        $herd->addIndex(['shepherd_id']);
        $herd->addIndex(['formed_on']);

        $elephpant = $schema->createTable(HerdProjector::TABLE_ELEPHPANT);
        $elephpant->addColumn('elephpant_id', 'guid');
        $elephpant->addColumn('herd_id', 'guid');
        $elephpant->addColumn('breed', 'breed', ['length' => 64]);
        $elephpant->addColumn('adopted_on', 'datetime');
        $elephpant->setPrimaryKey(['elephpant_id']);
        $elephpant->addForeignKeyConstraint(HerdProjector::TABLE_HERD, ['herd_id'], ['herd_id']);
        $elephpant->addIndex(['adopted_on']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(HerdProjector::TABLE_HERD);
        $schema->dropTable(HerdProjector::TABLE_ELEPHPANT);
    }
}

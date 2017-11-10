<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Elewant\Herding\Projections\HerdProjector;

class Version20171103163625 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $desiredBreeds = $schema->createTable(HerdProjector::TABLE_DESIRED_BREEDS);
        $desiredBreeds->addColumn('herd_id', 'guid');
        $desiredBreeds->addColumn('breed', 'breed', ['length' => 64]);
        $desiredBreeds->addColumn('desired_on', 'datetime');
        $desiredBreeds->setPrimaryKey(['herd_Id', 'breed']);
        $desiredBreeds->addForeignKeyConstraint(HerdProjector::TABLE_HERD, ['herd_id'], ['herd_id']);
        $desiredBreeds->addIndex(['desired_on']);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable(HerdProjector::TABLE_DESIRED_BREEDS);
    }
}

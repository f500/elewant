<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Elewant\Herding\Projections\HerdReadModel;

class Version20171103163625 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $desiredBreeds = $schema->createTable(HerdReadModel::TABLE_DESIRED_BREEDS);
        $desiredBreeds->addColumn('herd_id', 'guid');
        $desiredBreeds->addColumn('breed', 'breed', ['length' => 64]);
        $desiredBreeds->addColumn('desired_on', 'datetime');
        $desiredBreeds->setPrimaryKey(['herd_Id', 'breed']);
        $desiredBreeds->addForeignKeyConstraint(HerdReadModel::TABLE_HERD, ['herd_id'], ['herd_id']);
        $desiredBreeds->addIndex(['desired_on']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(HerdReadModel::TABLE_DESIRED_BREEDS);
    }
}

<?php

declare(strict_types=1);

namespace Tooling\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Elewant\Webapp\Infrastructure\ProophProjections\HerdReadModel;

final class Version20171103163625 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $desiredBreeds = $schema->createTable(HerdReadModel::TABLE_DESIRED_BREEDS);
        $desiredBreeds->addColumn('herd_id', 'guid');
        $desiredBreeds->addColumn('breed', 'breed', ['length' => 64]);
        $desiredBreeds->addColumn('desired_on', 'datetime');
        $desiredBreeds->setPrimaryKey(['herd_Id', 'breed']);
        $desiredBreeds->addForeignKeyConstraint(HerdReadModel::TABLE_HERD, ['herd_id'], ['herd_id']);
        $desiredBreeds->addIndex(['desired_on']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(HerdReadModel::TABLE_DESIRED_BREEDS);
    }
}

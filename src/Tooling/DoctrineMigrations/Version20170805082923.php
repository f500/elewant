<?php

declare(strict_types=1);

namespace Tooling\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Elewant\Webapp\Infrastructure\ProophProjections\HerdReadModel;

final class Version20170805082923 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $herd = $schema->createTable(HerdReadModel::TABLE_HERD);
        $herd->addColumn('herd_id', 'guid');
        $herd->addColumn('shepherd_id', 'guid');
        $herd->addColumn('name', 'string', ['length' => 64]);
        $herd->addColumn('formed_on', 'datetime');
        $herd->setPrimaryKey(['herd_id']);
        $herd->addIndex(['shepherd_id']);
        $herd->addIndex(['formed_on']);

        $elephpant = $schema->createTable(HerdReadModel::TABLE_ELEPHPANT);
        $elephpant->addColumn('elephpant_id', 'guid');
        $elephpant->addColumn('herd_id', 'guid');
        $elephpant->addColumn('breed', 'breed', ['length' => 64]);
        $elephpant->addColumn('adopted_on', 'datetime');
        $elephpant->setPrimaryKey(['elephpant_id']);
        $elephpant->addForeignKeyConstraint(HerdReadModel::TABLE_HERD, ['herd_id'], ['herd_id']);
        $elephpant->addIndex(['adopted_on']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(HerdReadModel::TABLE_HERD);
        $schema->dropTable(HerdReadModel::TABLE_ELEPHPANT);
    }
}

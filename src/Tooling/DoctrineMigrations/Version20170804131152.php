<?php

declare(strict_types=1);

namespace Tooling\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20170804131152 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $user = $schema->createTable('user');
        $user->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true, 'notNull' => true]);
        $user->addColumn('username', 'string', ['length' => 191, 'notNull' => true]);
        $user->addColumn('display_name', 'string', ['length' => 255, 'notNull' => true]);
        $user->addColumn('country', 'string', ['length' => 255, 'notNull' => true]);
        $user->addUniqueIndex(['username']);
        $user->setPrimaryKey(['id']);

        $connection = $schema->createTable('connection');
        $connection->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true, 'notNull' => true]);
        $connection->addColumn('user_id', 'integer', ['unsigned' => true, 'notNull' => true]);
        $connection->addColumn('resource', 'string', ['length' => 32, 'notNull' => true]);
        $connection->addColumn('resource_id', 'string', ['length' => 128, 'notNull' => true]);
        $connection->addColumn('access_token', 'string', ['length' => 255, 'notNull' => true]);
        $connection->addColumn('refresh_token', 'string', ['length' => 255, 'notNull' => true]);
        $connection->addIndex(['user_id']);
        $connection->addIndex(['resource', 'resource_id'], 'resource_idx');
        $connection->setPrimaryKey(['id']);
        $connection->addForeignKeyConstraint('user', ['user_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('connection');
        $schema->dropTable('user');
    }
}

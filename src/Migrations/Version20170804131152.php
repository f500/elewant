<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170804131152 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
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

    /**
     * @param Schema $schema
     *
     * @throws SchemaException
     */
    public function down(Schema $schema)
    {
        $connection = $schema->getTable('connection');
        $connection->dropIndex('FK_29F77366A76ED395');

        $schema->dropTable('connection');
        $schema->dropTable('user');
    }
}

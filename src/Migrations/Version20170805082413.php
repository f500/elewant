<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170805082413 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            file_get_contents(__DIR__ . '/../../config/scripts/mariadb/01_event_streams_table.sql')
        );
        $this->addSql(
            file_get_contents(__DIR__ . '/../../config/scripts/mariadb/02_projections_table.sql')
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('event_streams');
        $schema->dropTable('projections');
    }
}

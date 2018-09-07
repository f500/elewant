<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20180907111518 extends AbstractMigration
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

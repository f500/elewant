<?php

declare(strict_types=1);

namespace Tooling\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20180907111518 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function up(Schema $schema): void
    {
        $sql = (string) file_get_contents(__DIR__ . '/../../../config/scripts/mariadb/01_event_streams_table.sql');
        $this->addSql($sql);

        $sql = (string) file_get_contents(__DIR__ . '/../../../config/scripts/mariadb/02_projections_table.sql');
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('event_streams');
        $schema->dropTable('projections');
    }
}

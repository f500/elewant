<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170811124421 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD shepherd_id CHAR(36) NOT NULL COMMENT \'(DC2Type:shepherd_id)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493AE5C753 ON user (shepherd_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D6493AE5C753 ON user');
        $this->addSql('ALTER TABLE user DROP shepherd_id');
    }
}

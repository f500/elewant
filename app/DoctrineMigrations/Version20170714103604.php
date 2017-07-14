<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170714103604 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE breed (id VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, picture VARCHAR(300) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE elephpant (id INT AUTO_INCREMENT NOT NULL, herd_id INT NOT NULL, breed_id VARCHAR(255) NOT NULL, INDEX IDX_826BC539D35A8CCC (herd_id), INDEX IDX_826BC539A8B4A30F (breed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE herd (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE elephpant ADD CONSTRAINT FK_826BC539D35A8CCC FOREIGN KEY (herd_id) REFERENCES herd (id)');
        $this->addSql('ALTER TABLE elephpant ADD CONSTRAINT FK_826BC539A8B4A30F FOREIGN KEY (breed_id) REFERENCES breed (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE elephpant DROP FOREIGN KEY FK_826BC539A8B4A30F');
        $this->addSql('ALTER TABLE elephpant DROP FOREIGN KEY FK_826BC539D35A8CCC');
        $this->addSql('DROP TABLE breed');
        $this->addSql('DROP TABLE elephpant');
        $this->addSql('DROP TABLE herd');
    }
}

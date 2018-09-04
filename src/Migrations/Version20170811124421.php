<?php

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170811124421 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $userTable = $schema->getTable('user');
        $userTable->addColumn('shepherd_id', 'shepherd_id');
        $userTable->addUniqueIndex(['shepherd_id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $userTable = $schema->getTable('user');
        $userTable->dropIndex('UNIQ_8D93D6493AE5C753');
        $userTable->dropColumn('shepherd_id');
    }
}

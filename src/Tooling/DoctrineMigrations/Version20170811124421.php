<?php

declare(strict_types=1);

namespace Tooling\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\Migrations\AbstractMigration;

final class Version20170811124421 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    public function up(Schema $schema): void
    {
        $userTable = $schema->getTable('user');
        $userTable->addColumn('shepherd_id', 'shepherd_id');
        $userTable->addUniqueIndex(['shepherd_id']);
    }

    /**
     * @param Schema $schema
     * @throws SchemaException
     */
    public function down(Schema $schema): void
    {
        $userTable = $schema->getTable('user');
        $userTable->dropIndex('UNIQ_8D93D6493AE5C753');
        $userTable->dropColumn('shepherd_id');
    }
}

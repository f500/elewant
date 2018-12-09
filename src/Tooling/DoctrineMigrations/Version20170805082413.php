<?php

declare(strict_types=1);

namespace Tooling\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20170805082413 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        // We've updated the Prooph components, this method doesn't exist anymore.
        // Version 20180907111518 will create the new stream(s).

        // if (class_exists('Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema')) {
        //     EventStoreSchema::createSingleStream($schema, 'event_stream', true);
        // }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        // if (class_exists('Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema')) {
        //     EventStoreSchema::dropStream($schema);
        // }
    }
}

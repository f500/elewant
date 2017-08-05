<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema;

class Version20170805082413 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        if (class_exists('Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema')) {
            EventStoreSchema::createSingleStream($schema, 'event_stream', true);
        }

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        if (class_exists('Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema')) {
            EventStoreSchema::dropStream($schema);
        }
    }
}

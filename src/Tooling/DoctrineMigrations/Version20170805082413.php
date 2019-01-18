<?php

declare(strict_types=1);

namespace Tooling\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20170805082413 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function up(Schema $schema): void
    {
        $this->skipIf(true, 'Replaced by Version 20180907111518.');

        // We've updated the Prooph components, this method doesn't exist anymore.
        // Version 20180907111518 will create the new stream(s).

        // if (class_exists('Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema')) {
        //     EventStoreSchema::createSingleStream($schema, 'event_stream', true);
        // }
    }

    /**
     * @param Schema $schema
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function down(Schema $schema): void
    {
        $this->skipIf(true, 'Replaced by Version 20180907111518.');

        // if (class_exists('Prooph\EventStore\Adapter\Doctrine\Schema\EventStoreSchema')) {
        //     EventStoreSchema::dropStream($schema);
        // }
    }
}

<?php

declare(strict_types=1);

namespace Tooling\ProophEventStore;

use Iterator;
use Prooph\Common\Messaging\MessageConverter;
use Prooph\EventStore\Pdo\DefaultMessageConverter;
use Prooph\EventStore\Pdo\HasQueryHint;
use Prooph\EventStore\Pdo\MariaDBIndexedPersistenceStrategy;
use Prooph\EventStore\Pdo\PersistenceStrategy;
use Prooph\EventStore\StreamName;

final class MariaDbSingleStreamStrategyWithUnescapedUnicode implements PersistenceStrategy, HasQueryHint, MariaDBIndexedPersistenceStrategy
{
    /** @var MessageConverter */
    private $messageConverter;

    public function __construct(?MessageConverter $messageConverter = null)
    {
        $this->messageConverter = $messageConverter ?? new DefaultMessageConverter();
    }

    /**
     * @param string $tableName
     * @return string[]
     */
    public function createSchema(string $tableName): array
    {
        $statement = <<<EOT
CREATE TABLE `$tableName` (
    `no` BIGINT(20) NOT NULL AUTO_INCREMENT,
    `event_id` CHAR(36) COLLATE utf8mb4_bin NOT NULL,
    `event_name` VARCHAR(100) COLLATE utf8mb4_bin NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `metadata` LONGTEXT NOT NULL,
    `created_at` DATETIME(6) NOT NULL,
    `aggregate_version` INT(11) UNSIGNED GENERATED ALWAYS AS (JSON_EXTRACT(metadata, '$._aggregate_version')) STORED,
    `aggregate_id` CHAR(36) GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(metadata, '$._aggregate_id'))) STORED,
    `aggregate_type` VARCHAR(150) GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(metadata, '$._aggregate_type'))) STORED,
    CHECK (`payload` IS NOT NULL AND JSON_VALID(`payload`)),
    CHECK (`metadata` IS NOT NULL AND JSON_VALID(`metadata`)),
    PRIMARY KEY (`no`),
    UNIQUE KEY `ix_event_id` (`event_id`),
    UNIQUE KEY `ix_unique_event` (`aggregate_type`, `aggregate_id`, `aggregate_version`),
    KEY `ix_query_aggregate` (`aggregate_type`,`aggregate_id`,`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
EOT;

        return [$statement];
    }

    /** @return string[] */
    public function columnNames(): array
    {
        return [
            'event_id',
            'event_name',
            'payload',
            'metadata',
            'created_at',
        ];
    }

    /** @return string[] */
    public function indexedMetadataFields(): array
    {
        return [
            '_aggregate_id' => 'aggregate_id',
            '_aggregate_type' => 'aggregate_type',
            '_aggregate_version' => 'aggregate_version',
        ];
    }

    /**
     * @param Iterator $streamEvents
     * @return mixed[]
     */
    public function prepareData(Iterator $streamEvents): array
    {
        $data = [];

        foreach ($streamEvents as $event) {
            $eventData = $this->messageConverter->convertToArray($event);

            $data[] = $eventData['uuid'];
            $data[] = $eventData['message_name'];
            $data[] = json_encode($eventData['payload'], JSON_UNESCAPED_UNICODE);
            $data[] = json_encode($eventData['metadata']);
            /** @noinspection PhpUndefinedMethodInspection */
            $data[] = $eventData['created_at']->format('Y-m-d\TH:i:s.u');
        }

        return $data;
    }

    public function generateTableName(StreamName $streamName): string
    {
        return '_' . sha1($streamName->toString());
    }

    public function indexName(): string
    {
        return 'ix_query_aggregate';
    }
}

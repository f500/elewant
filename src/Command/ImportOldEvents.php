<?php

declare(strict_types=1);

namespace App\Command;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\StreamName;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportOldEvents extends ContainerAwareCommand
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    public function __construct(Connection $connection, MessageFactory $messageFactory)
    {
        $this->connection     = $connection;
        $this->messageFactory = $messageFactory;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('event-store:event-stream:import-old-events')
            ->setDescription('Import old events')
            ->setHelp('This command imports old events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->find('event-store:event-stream:delete')->run(new ArrayInput([]), $output);
        $this->getApplication()->find('event-store:event-stream:create')->run(new ArrayInput([]), $output);

        $events = $this->connection->fetchAll('SELECT * FROM event_stream ORDER BY created_at ASC');

        foreach ($events as $event) {
            $event['uuid']         = $event['event_id'];
            $event['message_name'] = $event['event_name'];
            $event['metadata']     = [
                '_aggregate_id'      => $event['aggregate_id'],
                '_aggregate_version' => (int) $event['version'],
                '_aggregate_type'    => $event['aggregate_type'],
            ];
            $event['created_at']   = new DateTimeImmutable($event['created_at']);
            $event['payload']      = json_decode($event['payload'], true);

            var_dump($event['payload']);
            var_dump(json_encode($event['payload'], JSON_UNESCAPED_UNICODE));
            var_dump(json_encode($event['payload'], JSON_UNESCAPED_UNICODE & JSON_UNESCAPED_SLASHES));

            $iterator = new \ArrayIterator(
                [$this->messageFactory->createMessageFromArray($event['event_name'], $event)]
            );

            /** @var EventStore $eventStore */
            $eventStore = $this->getContainer()->get('prooph_event_store.herd_store');
            $eventStore->appendTo(new StreamName('event_stream'), $iterator);
        }

        $output->writeln('<info>Events imported succesfully</info>');
    }
}

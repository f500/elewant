<?php

declare(strict_types=1);

namespace Tooling\SymfonyConsole;

use ArrayIterator;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateEventStreamCommand extends Command
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var string
     */
    protected static $defaultName = 'event-store:event-stream:create';

    protected function configure(): void
    {
        $this->setDescription('Create event_stream.')
            ->setHelp('This command creates the event_stream');
    }

    public function __construct(EventStore $eventStore)
    {
        parent::__construct();

        $this->eventStore = $eventStore;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $streamName = new StreamName('event_stream');

        if ($this->eventStore->hasStream($streamName)) {
            return 0;
        }

        $this->eventStore->create(new Stream($streamName, new ArrayIterator([])));
        $output->writeln('<info>Event stream was created successfully.</info>');

        return 0;
    }
}

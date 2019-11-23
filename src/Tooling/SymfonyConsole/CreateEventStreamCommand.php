<?php

declare(strict_types=1);

namespace Tooling\SymfonyConsole;

use ArrayIterator;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateEventStreamCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('event-store:event-stream:create')
            ->setDescription('Create event_stream.')
            ->setHelp('This command creates the event_stream');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var EventStore $eventStore */
        $eventStore = $this->getContainer()->get('prooph_event_store.herd_store');

        $streamName = new StreamName('event_stream');

        if ($eventStore->hasStream($streamName)) {
            return 0;
        }

        $eventStore->create(new Stream($streamName, new ArrayIterator([])));
        $output->writeln('<info>Event stream was created successfully.</info>');

        return 0;
    }
}

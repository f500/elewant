<?php

declare(strict_types=1);

namespace Tooling\SymfonyConsole;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Exception\StreamNotFound;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class DeleteEventStreamCommand extends Command
{
    private EventStore $eventStore;

    /**
     * @var string
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected static $defaultName = 'event-store:event-stream:delete';

    public function __construct(EventStore $eventStore)
    {
        parent::__construct();
        $this->eventStore = $eventStore;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            '<error>CAUTION! This will delete the event_store stream, are you sure? (y/N)</error>',
            false
        );

        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        try {
            $this->eventStore->delete(new StreamName('event_stream'));
        } catch (StreamNotFound $exception) {
            // Fine by us.
        }

        $output->writeln('<info>Event stream was deleted successfully.</info>');

        return 0;
    }
}

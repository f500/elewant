<?php

declare(strict_types=1);

namespace Tooling\SymfonyConsole;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Exception\StreamNotFound;
use Prooph\EventStore\StreamName;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class DeleteEventStreamCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this->setName('event-store:event-stream:delete');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            '<error>CAUTION! This will delete the event_store stream, are you sure? (y/N)</error>',
            false
        );

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        try {
            /** @var EventStore $eventStore */
            $eventStore = $this->getContainer()->get('prooph_event_store.herd_store');
            $eventStore->delete(new StreamName('event_stream'));
        } catch (StreamNotFound $exception) {
            // Fine by us.
        }

        $output->writeln('<info>Event stream was deleted successfully.</info>');
    }
}

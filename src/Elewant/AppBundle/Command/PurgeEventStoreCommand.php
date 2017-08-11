<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class PurgeEventStoreCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('eventstore:herd:purge');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('<error>CAUTION! This will empty the Event store, are you sure? (y/N)</error>', false);

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        /** @var Connection $connection */
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        $connection->executeUpdate('truncate table event_stream');
    }
}

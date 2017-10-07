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
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function configure()
    {
        $this->setName('eventstore:herd:purge');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper   = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            '<error>CAUTION! This will empty the Event store, are you sure? (y/N)</error>',
            false
        );

        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $this->connection->executeUpdate('truncate table event_stream');
    }
}

<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Command;

use Elewant\Herding\Projections\HerdReadModel;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\StreamName;
use Prooph\ServiceBus\EventBus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildHerdProjectionCommand extends ContainerAwareCommand
{
    /**
     * @var HerdReadModel
     */
    private $herdProjection;

    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var EventBus
     */
    private $replayBus;


    protected function configure()
    {
        $this->setName('projection:herd:rebuild');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->herdProjection->clearAllTables();

        $streamName = new StreamName('event_stream');
        $iterator   = $this->eventStore->replay([$streamName]);
//
//        foreach ($iterator as $key => $event) {
//            $this->replayBus->dispatch($event);
//        };
    }
}

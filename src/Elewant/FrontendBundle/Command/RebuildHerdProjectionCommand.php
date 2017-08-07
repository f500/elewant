<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Command;

use Elewant\Herding\Projections\HerdProjector;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\StreamName;
use Prooph\ServiceBus\EventBus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildHerdProjectionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('projection:herd:rebuild');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var HerdProjector $herdProjector */
        $herdProjector = $this->getContainer()->get('elewant.herd_projection.herd_projector');
        $herdProjector->clearAllTables();

        /** @var EventStore $eventStore */
        $eventStore = $this->getContainer()->get('prooph_event_store.herd_store');
        $streamName = new StreamName('event');
        $iterator   = $eventStore->replay([$streamName]);

        /** @var EventBus $replayBus */
        $replayBus = $this->getContainer()->get('prooph_service_bus.herd_replay_bus');
        foreach ($iterator as $key => $event) {
            $replayBus->dispatch($event);
        };
    }
}

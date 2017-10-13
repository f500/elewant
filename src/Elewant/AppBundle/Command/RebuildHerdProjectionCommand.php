<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Command;

use Elewant\Herding\Projections\HerdProjector;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\StreamName;
use Prooph\ServiceBus\EventBus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildHerdProjectionCommand extends ContainerAwareCommand
{
    /**
     * @var HerdProjector
     */
    private $herdProjector;
    /**
     * @var EventStore
     */
    private $eventStore;
    /**
     * @var EventBus
     */
    private $replayBus;

    public function __construct(HerdProjector $herdProjector, EventStore $eventStore, EventBus $replayBus)
    {
        $this->herdProjector = $herdProjector;
        $this->eventStore    = $eventStore;
        $this->replayBus     = $replayBus;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('projection:herd:rebuild');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->herdProjector->clearAllTables();

        $streamName = new StreamName('event');
        $iterator   = $this->eventStore->replay([$streamName]);

        foreach ($iterator as $key => $event) {
            $this->replayBus->dispatch($event);
        };
    }
}

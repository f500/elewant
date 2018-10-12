<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Handlers;

use Elewant\Herding\Application\Commands\RenameHerd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\SorryIDoNotHaveThat;

class RenameHerdHandler
{
    /**
     * @var HerdCollection
     */
    private $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    public function __invoke(RenameHerd $command)
    {
        $herd = $this->herdCollection->get($command->herdId());
        if (!$herd) {
            throw SorryIDoNotHaveThat::herd($command->herdId());
        }

        $herd->rename($command->newHerdName());
        $this->herdCollection->save($herd);
    }
}

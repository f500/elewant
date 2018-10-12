<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Handlers;

use Elewant\Herding\Application\Commands\EliminateDesireForBreed;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\SorryIDoNotHaveThat;

final class EliminateDesireForBreedHandler
{
    /**
     * @var HerdCollection
     */
    private $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    public function __invoke(EliminateDesireForBreed $command)
    {
        $herd = $this->herdCollection->get($command->herdId());
        if (!$herd) {
            throw SorryIDoNotHaveThat::herd($command->herdId());
        }

        $herd->eliminateDesireForBreed($command->breed());
        $this->herdCollection->save($herd);
    }
}

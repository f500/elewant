<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Commands\DesireBreed;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\SorryIDoNotHaveThat;

final class DesireBreedHandler
{
    /**
     * @var HerdCollection
     */
    private $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    public function __invoke(DesireBreed $command)
    {
        $herd = $this->herdCollection->get($command->herdId());
        if (!$herd) {
            throw SorryIDoNotHaveThat::herd($command->herdId());
        }

        $herd->desireBreed($command->breed());
        $this->herdCollection->save($herd);
    }
}

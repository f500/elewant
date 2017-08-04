<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Commands\AbandonElePHPant;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\SorryIDoNotHaveThat;

final class AbandonElePHPantHandler
{
    /**
     * @var HerdCollection
     */
    private $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    public function __invoke(AbandonElePHPant $command)
    {
        $herd = $this->herdCollection->get($command->herdId());
        if (!$herd) {
            SorryIDoNotHaveThat::herd($command->herdId());
        }

        $herd->abandonElePHPant($command->breed());
        $this->herdCollection->save($herd);
    }

}

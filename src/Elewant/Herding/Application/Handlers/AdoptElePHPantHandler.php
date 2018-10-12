<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Handlers;

use Elewant\Herding\Application\Commands\AdoptElePHPant;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\SorryIDoNotHaveThat;

final class AdoptElePHPantHandler
{
    /**
     * @var HerdCollection
     */
    private $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    public function __invoke(AdoptElePHPant $command)
    {
        $herd = $this->herdCollection->get($command->herdId());
        if (!$herd) {
            throw SorryIDoNotHaveThat::herd($command->herdId());
        }

        $herd->adoptElePHPant($command->breed());
        $this->herdCollection->save($herd);
    }
}

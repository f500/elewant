<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\HerdCollection;
use Elewant\Herding\DomainModel\SorryICanNotChangeHerd;
use Elewant\Herding\DomainModel\SorryIDoNotHaveThat;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;

final class AdoptElePHPantHandler
{
    private HerdCollection $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    /**
     * @param AdoptElePHPant $command
     * @throws SorryICanNotChangeHerd
     * @throws SorryIDoNotHaveThat
     * @throws SorryThatIsAnInvalid
     */
    public function __invoke(AdoptElePHPant $command): void
    {
        $herd = $this->herdCollection->get($command->herdId());

        if (!$herd) {
            throw SorryIDoNotHaveThat::herd($command->herdId());
        }

        $herd->adoptElePHPant($command->breed());
        $this->herdCollection->save($herd);
    }
}

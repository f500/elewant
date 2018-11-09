<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\HerdCollection;
use Elewant\Herding\DomainModel\SorryICanNotChangeHerd;
use Elewant\Herding\DomainModel\SorryIDoNotHaveThat;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;

final class AbandonHerdHandler
{
    /**
     * @var HerdCollection
     */
    private $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    /**
     * @param AbandonHerd $command
     *
     * @throws SorryIDoNotHaveThat
     * @throws SorryICanNotChangeHerd
     * @throws SorryThatIsAnInvalid
     */
    public function __invoke(AbandonHerd $command): void
    {
        $herd = $this->herdCollection->get($command->herdId());
        if (!$herd) {
            throw SorryIDoNotHaveThat::herd($command->herdId());
        }
        if (!$herd->shepherdId()->equals($command->shepherdId())) {
            throw SorryIDoNotHaveThat::herd($command->herdId());
        }

        $herd->abandon();
        $this->herdCollection->save($herd);
    }
}

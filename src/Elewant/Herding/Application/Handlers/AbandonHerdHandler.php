<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Handlers;

use Elewant\Herding\Application\Commands\AbandonHerd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\SorryIDoNotHaveThat;

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

    public function __invoke(AbandonHerd $command)
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

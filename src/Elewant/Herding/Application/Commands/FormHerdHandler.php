<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\Herd;
use Elewant\Herding\DomainModel\Herd\HerdCollection;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;

final class FormHerdHandler
{
    private HerdCollection $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    /**
     * @param FormHerd $command
     * @throws SorryThatIsAnInvalid
     */
    public function __invoke(FormHerd $command): void
    {
        $herd = Herd::form($command->shepherdId(), $command->herdName());
        $this->herdCollection->save($herd);
    }
}

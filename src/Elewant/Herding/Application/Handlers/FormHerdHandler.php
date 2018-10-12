<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Handlers;

use Elewant\Herding\Application\Commands\FormHerd;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;

final class FormHerdHandler
{
    /**
     * @var HerdCollection
     */
    private $herdCollection;

    public function __construct(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
    }

    public function __invoke(FormHerd $command)
    {
        $herd = Herd::form($command->shepherdId(), $command->herdName());
        $this->herdCollection->save($herd);
    }
}

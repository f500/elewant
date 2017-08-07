<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Commands\AbandonHerd;
use Elewant\Herding\Model\Commands\FormHerd;
use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\ShepherdId;
use Webmozart\Assert\Assert;

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

    /**
     * @param FormHerd $command
     */
    public function __invoke(FormHerd $command)
    {
        $herd = Herd::form($command->shepherdId(), $command->herdName());
        $this->herdCollection->save($herd);
    }
}

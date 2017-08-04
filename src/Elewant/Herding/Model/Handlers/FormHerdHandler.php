<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Command\FormHerd;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\ShepherdId;

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
        $shepherdId = ShepherdId::fromString($command->userId());

        $herd = Herd::form($shepherdId, $command->herdName());
        $this->herdCollection->save($herd);
    }

}

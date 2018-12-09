<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class AbandonHerd extends Command
{
    use PayloadTrait;

    public static function forShepherd(string $herdId, string $shepherdId): self
    {
        return new self(['herdId' => $herdId, 'shepherdId' => $shepherdId]);
    }

    /**
     * @throws SorryThatIsAnInvalid
     */
    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    /**
     * @throws SorryThatIsAnInvalid
     */
    public function shepherdId(): ShepherdId
    {
        return ShepherdId::fromString($this->payload['shepherdId']);
    }
}

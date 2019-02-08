<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class FormHerd extends Command
{
    use PayloadTrait;

    public static function forShepherd(string $shepherdId, string $herdName): self
    {
        return new self(['shepherdId' => $shepherdId, 'herdName' => $herdName]);
    }

    /**
     * @return ShepherdId
     * @throws SorryThatIsAnInvalid
     */
    public function shepherdId(): ShepherdId
    {
        return ShepherdId::fromString($this->payload['shepherdId']);
    }

    public function herdName(): string
    {
        return $this->payload['herdName'];
    }
}

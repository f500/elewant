<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class RenameHerd extends Command
{
    use PayloadTrait;

    public static function forShepherd(string $herdId, string $newHerdName): self
    {
        return new self(['herdId' => $herdId, 'newHerdName' => $newHerdName]);
    }

    /**
     * @throws SorryThatIsAnInvalid
     */
    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    public function newHerdName(): string
    {
        return $this->payload['newHerdName'];
    }
}

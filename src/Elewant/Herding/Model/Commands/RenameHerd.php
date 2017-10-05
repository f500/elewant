<?php

namespace Elewant\Herding\Model\Commands;

use Elewant\Herding\Model\HerdId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class RenameHerd extends Command
{
    use PayloadTrait;

    public static function forShepherd(string $herdId, string $newHerdName): self
    {
        return new self(['herdId' => $herdId, 'newHerdName' => $newHerdName]);
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    public function newHerdName(): string
    {
        return $this->payload['newHerdName'];
    }
}

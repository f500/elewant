<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Commands;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\HerdId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class AbandonElePHPant extends Command
{
    use PayloadTrait;

    public static function byHerd(string $herdId, string $breed): self
    {
        return new self(['herdId' => $herdId, 'breed' => $breed]);
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    public function breed(): Breed
    {
        return Breed::fromString($this->payload['breed']);
    }
}


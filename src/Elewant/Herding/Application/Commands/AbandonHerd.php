<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class AbandonHerd extends Command
{
    use PayloadTrait;

    public static function forShepherd(string $herdId, string $shepherdId): self
    {
        return new self(['herdId' => $herdId, 'shepherdId' => $shepherdId]);
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    public function shepherdId(): ShepherdId
    {
        return ShepherdId::fromString($this->payload['shepherdId']);
    }
}

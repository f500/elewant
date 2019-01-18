<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

final class AbandonElePHPant extends Command
{
    use PayloadTrait;

    public static function byHerd(string $herdId, string $breed): self
    {
        return new self(['herdId' => $herdId, 'breed' => $breed]);
    }

    /**
     * @return HerdId
     * @throws SorryThatIsAnInvalid
     */
    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    /**
     * @return Breed
     * @throws SorryThatIsAnInvalid
     */
    public function breed(): Breed
    {
        return Breed::fromString($this->payload['breed']);
    }
}

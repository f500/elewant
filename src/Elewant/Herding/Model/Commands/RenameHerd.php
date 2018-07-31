<?php

declare(strict_types=1);

namespace Elewant\Herding\Model\Commands;

use Elewant\Herding\Model\HerdId;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Webmozart\Assert\Assert;

class RenameHerd extends Command
{
    use PayloadTrait;

    public static function forShepherd(string $herdId, string $newHerdName): self
    {
        $renameHerd = new self(['herdId' => $herdId, 'newHerdName' => $newHerdName]);
        $renameHerd->protect();

        return $renameHerd;
    }

    public function herdId(): HerdId
    {
        return HerdId::fromString($this->payload['herdId']);
    }

    public function newHerdName(): string
    {
        return $this->payload['newHerdName'];
    }

    private function protect()
    {
        $newHerdName = $this->payload['newHerdName'];

        Assert::lengthBetween($newHerdName, 1, 50);
        Assert::regex($newHerdName, '/^[^\s]+.*[^\s]+$/');
    }
}

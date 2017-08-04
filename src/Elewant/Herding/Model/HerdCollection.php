<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

interface HerdCollection
{
    public function save(Herd $herd): void;

    public function get(HerdId $herdId): ?Herd;
}

<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

interface HerdCollection
{
    public function save(Herd $user): void;

    public function get(HerdId $herdId): ?Herd;
}

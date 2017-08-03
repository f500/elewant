<?php

namespace Elewant\Herding\Model;

interface HerdCollection
{
    public function save(Herd $user): void;

    public function get(HerdId $herdId): ?Herd;
}

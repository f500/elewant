<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

interface HerdCollection
{
    public function save(Herd $herd): void;

    public function get(HerdId $herdId): ?Herd;
}

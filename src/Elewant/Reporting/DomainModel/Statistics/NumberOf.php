<?php

declare(strict_types=1);

namespace Elewant\Reporting\DomainModel\Statistics;

interface NumberOf
{
    public function newHerdsFormedBetween(\DateTimeInterface $from, \DateTimeInterface $to): int;

    public function herdsEverFormed(): int;

    public function newElePHPantsAdoptedBetween(\DateTimeInterface $from, \DateTimeInterface $to): int;
}

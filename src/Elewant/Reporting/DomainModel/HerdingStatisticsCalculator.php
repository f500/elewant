<?php

declare(strict_types=1);

namespace Elewant\Reporting\DomainModel;

use DateTimeInterface;

interface HerdingStatisticsCalculator
{
    public function generate(DateTimeInterface $from, DateTimeInterface $to): CalculatedHerdingStatistics;
}

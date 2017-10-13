<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Service;

use DateTimeInterface;
use Elewant\AppBundle\Statistics\CalculatedHerdingStatistics;

interface HerdingStatisticsCalculator
{
    public function generate(DateTimeInterface $from, DateTimeInterface $to): CalculatedHerdingStatistics;
}

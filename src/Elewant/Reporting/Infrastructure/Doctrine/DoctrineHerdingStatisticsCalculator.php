<?php

declare(strict_types=1);

namespace Elewant\Reporting\Infrastructure\Doctrine;

use DateTimeInterface;
use Elewant\Reporting\DomainModel\CalculatedHerdingStatistics;
use Elewant\Reporting\DomainModel\HerdingStatisticsCalculator;
use Elewant\Reporting\DomainModel\Statistics\NumberOf;

final class DoctrineHerdingStatisticsCalculator implements HerdingStatisticsCalculator
{
    /**
     * @var NumberOf
     */
    private $numberOf;

    public function __construct(NumberOf $numberOf)
    {
        $this->numberOf = $numberOf;
    }

    public function generate(DateTimeInterface $from, DateTimeInterface $to): CalculatedHerdingStatistics
    {
        return new CalculatedHerdingStatistics(
            $from,
            $to,
            $this->numberOf->newHerdsFormedBetween($from, $to),
            $this->numberOf->newElePHPantsAdoptedBetween($from, $to)
        );
    }
}

<?php

declare(strict_types=1);

namespace Elewant\Reporting\DomainModel;

use Symfony\Contracts\EventDispatcher\Event;

final class HerdingStatisticsGenerated extends Event
{
    public const NAME = 'app.herding.statistics.generated';

    /**
     * @var CalculatedHerdingStatistics
     */
    private $statistics;

    public function __construct(CalculatedHerdingStatistics $statistics)
    {
        $this->statistics = $statistics;
    }

    public function statistics(): CalculatedHerdingStatistics
    {
        return $this->statistics;
    }
}

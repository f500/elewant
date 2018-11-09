<?php

declare(strict_types=1);

namespace Elewant\Reporting\Infrastructure\Doctrine;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Elewant\Reporting\DomainModel\CalculatedHerdingStatistics;
use Elewant\Reporting\DomainModel\HerdingStatisticsCalculator;
use Elewant\Webapp\DomainModel\Herding\Herd;
use Elewant\Webapp\DomainModel\Herding\HerdRepository;

/** @todo Is it ok to use the "Webapp" bounded-context here? */
final class DoctrineHerdingStatisticsCalculator implements HerdingStatisticsCalculator
{
    /**
     * @var HerdRepository
     */
    private $herdRepository;

    public function __construct(HerdRepository $herdRepository)
    {
        $this->herdRepository = $herdRepository;
    }

    public function generate(DateTimeInterface $from, DateTimeInterface $to): CalculatedHerdingStatistics
    {
        $herds = $this->herdsCreatedBetween($from, $to);

        return new CalculatedHerdingStatistics(
            $from,
            $to,
            $this->numberOfNewHerds($herds),
            $this->numberOfNewElePHPants($herds)
        );
    }

    private function numberOfNewHerds(Collection $herds): int
    {
        return count($herds);
    }

    private function numberOfNewElePHPants(Collection $herds): int
    {
        return array_reduce(
            $herds->toArray(),
            function (int $totalCount, Herd $herd): int {
                return $totalCount + count($herd->elePHPants());
            },
            0
        );
    }

    private function herdsCreatedBetween(DateTimeInterface $from, DateTimeInterface $to): Collection
    {
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->gt('formedOn', $from))
            ->andWhere($criteria->expr()->lte('formedOn', $to));

        return $this->herdRepository->matching($criteria);
    }
}

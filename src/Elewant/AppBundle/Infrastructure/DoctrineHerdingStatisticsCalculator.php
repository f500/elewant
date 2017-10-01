<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Infrastructure;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Elewant\AppBundle\Entity\Herd;
use Elewant\AppBundle\Repository\HerdRepository;
use Elewant\AppBundle\Service\HerdingStatisticsCalculator;
use Elewant\AppBundle\Statistics\CalculatedHerdingStatistics;

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
            $this->numberOfNewElephpants($herds)
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
            function ($totalCount, Herd $herd) { return $totalCount + count($herd->elePHPants()); },
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

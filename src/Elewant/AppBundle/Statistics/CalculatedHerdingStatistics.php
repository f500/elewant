<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Statistics;

use DateTime;
use DateTimeInterface;

final class CalculatedHerdingStatistics
{
    /**
     * @var DateTimeInterface
     */
    private $from;

    /**
     * @var DateTimeInterface
     */
    private $to;

    /**
     * @var int
     */
    private $numberOfNewHerds;

    /**
     * @var int
     */
    private $numberOfNewElePHPants;

    public function __construct(
        DateTimeInterface $from,
        DateTimeInterface $to,
        int $numberOfNewHerds,
        int $numberOfNewElePHPants
    ) {
        $this->from                  = $from;
        $this->to                    = $to;
        $this->numberOfNewHerds      = $numberOfNewHerds;
        $this->numberOfNewElePHPants = $numberOfNewElePHPants;
    }

    public function from(): DateTimeInterface
    {
        return $this->from;
    }

    public function to(): DateTimeInterface
    {
        return $this->to;
    }

    public function numberOfNewHerds(): int
    {
        return $this->numberOfNewHerds;
    }

    public function numberOfNewElePHPants(): int
    {
        return $this->numberOfNewElePHPants;
    }
}

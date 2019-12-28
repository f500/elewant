<?php

declare(strict_types=1);

namespace Elewant\Reporting\DomainModel;

use DateTimeInterface as DateTimeInterfaceAlias;

final class CalculatedHerdingStatistics
{
    private DateTimeInterfaceAlias $from;

    private DateTimeInterfaceAlias $to;

    private int $numberOfNewHerds;

    private int $numberOfNewElePHPants;

    public function __construct(
        DateTimeInterfaceAlias $from,
        DateTimeInterfaceAlias $to,
        int $numberOfNewHerds,
        int $numberOfNewElePHPants
    )
    {
        $this->from = $from;
        $this->to = $to;
        $this->numberOfNewHerds = $numberOfNewHerds;
        $this->numberOfNewElePHPants = $numberOfNewElePHPants;
    }

    public function from(): DateTimeInterfaceAlias
    {
        return $this->from;
    }

    public function to(): DateTimeInterfaceAlias
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

<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Elewant\Herding\DomainModel\ShepherdId;
use PhpSpec\ObjectBehavior;

final class HerdWasFormedSpec extends ObjectBehavior
{
    public function it_took_place(): void
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $shepherdId = ShepherdId::fromString('00000000-0000-0000-0000-000000000001');
        $name = 'Herd name';

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                $shepherdId,
                $name,
            ]
        );

        $this->shouldHaveType(HerdWasFormed::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->shepherdId()->shouldEqual($shepherdId);
        $this->name()->shouldEqual($name);
    }
}

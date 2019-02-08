<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Elewant\Herding\DomainModel\ShepherdId;
use PhpSpec\ObjectBehavior;

final class HerdWasAbandonedSpec extends ObjectBehavior
{
    public function it_took_place(): void
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $shepherdId = ShepherdId::fromString('10000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                $shepherdId,
            ]
        );

        $this->shouldHaveType(HerdWasAbandoned::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->shepherdId()->shouldEqual($shepherdId);
    }
}

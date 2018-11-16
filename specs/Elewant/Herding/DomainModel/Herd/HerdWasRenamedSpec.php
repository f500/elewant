<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use PhpSpec\ObjectBehavior;

final class HerdWasRenamedSpec extends ObjectBehavior
{
    public function it_took_place(): void
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough(
            'tookPlace',
            [
                $herdId,
                'new herd name',
            ]
        );

        $this->shouldHaveType(HerdWasRenamed::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldEqual($herdId);
        $this->newHerdName()->shouldEqual('new herd name');
    }
}

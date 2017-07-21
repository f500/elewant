<?php

namespace spec\Elewant\Domain\Events;

use Elewant\Domain\ElePHPant;
use Elewant\Domain\ElePHPantId;
use Elewant\Domain\Events\ElePHPantHasJoinedHerd;
use Elewant\Domain\HerdId;
use PhpSpec\ObjectBehavior;

class ElePHPantHasJoinedHerdSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $elePHPantId = ElePHPantId::fromString('10000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough('tookPlace', [
            $herdId,
            $elePHPantId,
            ElePHPant::BLUE
        ]);

        $this->shouldHaveType(ElePHPantHasJoinedHerd::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldBeEqual($herdId);
        $this->elePHPantId()->shouldBeEqual($elePHPantId);
        $this->elePHPantType()->shouldReturn(ElePHPant::BLUE);
    }

    public function getMatchers()
    {
        return [
            'beEqual' => function ($subject, $other) {
                return $subject->equals($other);
            }
        ];
    }
}

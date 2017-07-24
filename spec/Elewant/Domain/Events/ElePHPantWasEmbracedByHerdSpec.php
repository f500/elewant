<?php

namespace spec\Elewant\Domain\Events;

use Elewant\Domain\Breed;
use Elewant\Domain\ElePHPantId;
use Elewant\Domain\Events\ElePHPantWasEmbracedByHerd;
use Elewant\Domain\HerdId;
use PhpSpec\ObjectBehavior;

class ElePHPantWasEmbracedByHerdSpec extends ObjectBehavior
{
    function it_took_place()
    {
        $herdId = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $elePHPantId = ElePHPantId::fromString('10000000-0000-0000-0000-000000000000');

        $this->beConstructedThrough('tookPlace', [
            $herdId,
            $elePHPantId,
            Breed::fromString(Breed::BLUE)
        ]);

        $this->shouldHaveType(ElePHPantWasEmbracedByHerd::class);
        $this->aggregateId()->shouldReturn($herdId->toString());
        $this->herdId()->shouldBeEqual($herdId);
        $this->elePHPantId()->shouldBeEqual($elePHPantId);
        $this->breed()->shouldBeEqual(Breed::fromString(Breed::BLUE));
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

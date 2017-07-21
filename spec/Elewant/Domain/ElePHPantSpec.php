<?php

namespace spec\Elewant\Domain;

use Elewant\Domain\ElePHPant;
use Elewant\Domain\ElePHPantId;
use PhpSpec\ObjectBehavior;

class ElePHPantSpec extends ObjectBehavior
{
    function it_appears()
    {
        $elePHPantId = ElePHPantId::generate();

        $this->beConstructedThrough('appear', [$elePHPantId, ELEPHPant::BLUE]);
        $this->shouldHaveType(ElePHPant::class);
        $this->elePHPantId()->shouldBeEqual($elePHPantId);
        $this->type()->shouldReturn(ElePHPant::BLUE);
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

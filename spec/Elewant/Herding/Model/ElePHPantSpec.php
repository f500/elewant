<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\ElePHPant;
use Elewant\Herding\Model\ElePHPantId;
use Elewant\Herding\Model\Breed;
use PhpSpec\ObjectBehavior;

class ElePHPantSpec extends ObjectBehavior
{
    function it_appears()
    {
        $elePHPantId = ElePHPantId::generate();

        $this->beConstructedThrough('appear', [$elePHPantId, Breed::fromString(Breed::BLUE)]);
        $this->shouldHaveType(ElePHPant::class);
        $this->elePHPantId()->shouldEqual($elePHPantId);
        $this->type()->shouldEqual(Breed::fromString(Breed::BLUE));
    }

}

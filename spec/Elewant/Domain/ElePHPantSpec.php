<?php

namespace spec\Elewant\Domain;

use Elewant\Domain\ElePHPant;
use Elewant\Domain\ElePHPantId;
use Elewant\Domain\Breed;
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

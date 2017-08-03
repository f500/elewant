<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\SorryThatIsAnInvalid;
use PhpSpec\ObjectBehavior;

class BreedSpec extends ObjectBehavior
{
    function it_is_constructed_from_string()
    {
        $this->beConstructedThrough('fromString', ['green']);
        $this->shouldHaveType(Breed::class);
        $this->getType()->shouldReturn('green');
    }

    function it_converts_to_string()
    {
        $this->beConstructedThrough('fromString', ['green']);
        $this->shouldHaveType(Breed::class);
        $this->toString()->shouldReturn('green');
    }

    function it_does_not_construct_an_invalid_type()
    {
        $this->beConstructedThrough('fromString', ['invalid']);
        $this->shouldThrow(SorryThatIsAnInvalid::class)->duringInstantiation();
    }

    function it_equals_another_or_not()
    {
        $this->beConstructedThrough('fromString', [Breed::BLUE]);

        $isEqual = Breed::fromString(Breed::BLUE);
        $isNotEqual = Breed::fromString(Breed::GREEN);

        $this->equals($isEqual)->shouldReturn(true);
        $this->equals($isNotEqual)->shouldReturn(false);
    }

}

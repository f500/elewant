<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\SorryThatIsAnInvalid;
use PhpSpec\ObjectBehavior;

class BreedSpec extends ObjectBehavior
{
    function it_is_constructed_from_string()
    {
        $this->beConstructedThrough('fromString', ['BLUE_ORIGINAL_REGULAR']);
        $this->shouldHaveType(Breed::class);
        $this->getType()->shouldReturn('BLUE_ORIGINAL_REGULAR');
    }

    function it_converts_to_string()
    {
        $this->beConstructedThrough('fromString', ['BLUE_ORIGINAL_REGULAR']);
        $this->shouldHaveType(Breed::class);
        $this->toString()->shouldReturn('BLUE_ORIGINAL_REGULAR');
    }

    function it_does_not_construct_an_invalid_type()
    {
        $this->beConstructedThrough('fromString', ['invalid']);
        $this->shouldThrow(SorryThatIsAnInvalid::class)->duringInstantiation();
    }

    function it_equals_another_or_not()
    {
        $this->beConstructedThrough('fromString', [Breed::BLUE_SHOPWARE_LARGE]);

        $isEqual    = Breed::fromString(Breed::BLUE_SHOPWARE_LARGE);
        $isNotEqual = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $this->equals($isEqual)->shouldReturn(true);
        $this->equals($isNotEqual)->shouldReturn(false);
    }

}

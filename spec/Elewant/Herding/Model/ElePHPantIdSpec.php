<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\ElePHPantId;
use PhpSpec\ObjectBehavior;

class ElePHPantIdSpec extends ObjectBehavior
{
    function it_generates_a_new_one()
    {
        $this->beConstructedThrough('generate');
        $this->shouldHaveType(ElePHPantId::class);
        $this->toString()->shouldBeString();
    }

    function it_converts_from_and_tostring()
    {
        $this->beConstructedThrough('fromString', ['00000000-0000-0000-0000-000000000000']);
        $this->shouldHaveType(ElePHPantId::class);
        $this->toString()->shouldReturn('00000000-0000-0000-0000-000000000000');
    }

    function it_equals_another_or_not()
    {
        $this->beConstructedThrough('fromString', ['00000000-0000-0000-0000-000000000000']);

        $isEqual = ElePHPantId::fromString('00000000-0000-0000-0000-000000000000');
        $isNotEqual = ElePHPantId::fromString('11111111-1111-1111-1111-111111111111');

        $this->equals($isEqual)->shouldReturn(true);
        $this->equals($isNotEqual)->shouldReturn(false);
    }
}

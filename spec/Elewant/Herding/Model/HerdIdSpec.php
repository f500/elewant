<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\HerdId;
use PhpSpec\ObjectBehavior;

class HerdIdSpec extends ObjectBehavior
{
    function it_generates_a_new_one()
    {
        $this->beConstructedThrough('generate');
        $this->shouldHaveType(HerdId::class);
        $this->toString()->shouldBeString();
    }

    function it_converts_from_and_tostring()
    {
        $this->beConstructedThrough('fromString', ['00000000-0000-0000-0000-000000000000']);
        $this->shouldHaveType(HerdId::class);
        $this->toString()->shouldReturn('00000000-0000-0000-0000-000000000000');
    }

    function it_equals_another_or_not()
    {
        $this->beConstructedThrough('fromString', ['00000000-0000-0000-0000-000000000000']);

        $isEqual = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $isNotEqual = HerdId::fromString('11111111-1111-1111-1111-111111111111');

        $this->equals($isEqual)->shouldReturn(true);
        $this->equals($isNotEqual)->shouldReturn(false);
    }
}

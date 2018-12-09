<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use PhpSpec\ObjectBehavior;

final class HerdIdSpec extends ObjectBehavior
{
    public function it_generates_a_new_one(): void
    {
        $this->beConstructedThrough('generate');
        $this->shouldHaveType(HerdId::class);
        $this->toString()->shouldBeString();
    }

    public function it_converts_from_and_tostring(): void
    {
        $this->beConstructedThrough('fromString', ['00000000-0000-0000-0000-000000000000']);
        $this->shouldHaveType(HerdId::class);
        $this->toString()->shouldReturn('00000000-0000-0000-0000-000000000000');
    }

    public function it_equals_another_or_not(): void
    {
        $this->beConstructedThrough('fromString', ['00000000-0000-0000-0000-000000000000']);

        $isEqual    = HerdId::fromString('00000000-0000-0000-0000-000000000000');
        $isNotEqual = HerdId::fromString('11111111-1111-1111-1111-111111111111');

        $this->equals($isEqual)->shouldReturn(true);
        $this->equals($isNotEqual)->shouldReturn(false);
    }
}

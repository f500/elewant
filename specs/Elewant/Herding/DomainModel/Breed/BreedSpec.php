<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Breed;

use PhpSpec\ObjectBehavior;

final class BreedSpec extends ObjectBehavior
{
    public function it_is_constructed_from_string(): void
    {
        $this->beConstructedThrough('fromString', ['BLUE_ORIGINAL_REGULAR']);
        $this->shouldHaveType(Breed::class);
        $this->getType()->shouldReturn('BLUE_ORIGINAL_REGULAR');
    }

    public function it_converts_to_string(): void
    {
        $this->beConstructedThrough('fromString', ['BLUE_ORIGINAL_REGULAR']);
        $this->shouldHaveType(Breed::class);
        $this->isUnknown()->shouldReturn(false);
        $this->toString()->shouldReturn('BLUE_ORIGINAL_REGULAR');
    }

    public function it_is_unknown_when_given_an_invalid_type(): void
    {
        $this->beConstructedThrough('fromString', ['invalid']);
        $this->shouldHaveType(Breed::class);
        $this->isUnknown()->shouldReturn(true);
        $this->toString()->shouldReturn('UNKNOWN');
    }

    public function it_equals_another_or_not(): void
    {
        $this->beConstructedThrough('fromString', [Breed::BLUE_SHOPWARE_LARGE]);

        $isEqual = Breed::fromString(Breed::BLUE_SHOPWARE_LARGE);
        $isNotEqual = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $this->equals($isEqual)->shouldReturn(true);
        $this->equals($isNotEqual)->shouldReturn(false);
    }
}

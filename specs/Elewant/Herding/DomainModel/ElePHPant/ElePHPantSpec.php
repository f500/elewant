<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\ElePHPant;

use Elewant\Herding\DomainModel\Breed\Breed;
use PhpSpec\ObjectBehavior;

final class ElePHPantSpec extends ObjectBehavior
{
    public function it_appears(): void
    {
        $elePHPantId = ElePHPantId::generate();

        $this->beConstructedThrough('appear', [$elePHPantId, Breed::fromString(Breed::BLUE_ORIGINAL_REGULAR)]);
        $this->shouldHaveType(ElePHPant::class);
        $this->elePHPantId()->shouldEqual($elePHPantId);
        $this->breed()->shouldEqual(Breed::fromString(Breed::BLUE_ORIGINAL_REGULAR));
    }
}

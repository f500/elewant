<?php

namespace spec\Elewant\Herding\Model\Commands;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Application\Commands\AbandonElePHPant;
use Elewant\Herding\Model\HerdId;
use PhpSpec\ObjectBehavior;

class AbandonElePHPantSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedThrough('byHerd', ['00000000-0000-0000-0000-000000000000', Breed::RED_LARAVEL_LARGE]);
        $this->shouldHaveType(AbandonElePHPant::class);
        $this->herdId()->shouldEqual(HerdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->breed()->shouldEqual(Breed::redLaravelLarge());
    }
}

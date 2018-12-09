<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use PhpSpec\ObjectBehavior;

final class AbandonHerdSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->beConstructedThrough(
            'forShepherd',
            [
                '00000000-0000-0000-0000-000000000000',
                '10000000-0000-0000-0000-000000000000',
            ]
        );
        $this->shouldHaveType(AbandonHerd::class);
        $this->herdId()->shouldEqual(HerdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->shepherdId()->shouldEqual(ShepherdId::fromString('10000000-0000-0000-0000-000000000000'));
    }
}

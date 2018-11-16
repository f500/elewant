<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\ShepherdId;
use PhpSpec\ObjectBehavior;

final class FormHerdSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->beConstructedThrough(
            'forShepherd',
            [
                '00000000-0000-0000-0000-000000000000',
                'Herd is the word',
            ]
        );
        $this->shouldHaveType(FormHerd::class);
        $this->shepherdId()->shouldEqual(ShepherdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->herdName()->shouldBe('Herd is the word');
    }
}

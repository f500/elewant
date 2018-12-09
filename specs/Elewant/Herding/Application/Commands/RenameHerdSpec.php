<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\HerdId;
use PhpSpec\ObjectBehavior;

final class RenameHerdSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->beConstructedThrough(
            'forShepherd',
            [
                '00000000-0000-0000-0000-000000000000',
                'My shiny new herd name is: Ughghg',
            ]
        );
        $this->shouldHaveType(RenameHerd::class);
        $this->herdId()->shouldEqual(HerdId::fromString('00000000-0000-0000-0000-000000000000'));
        $this->newHerdName()->shouldBe('My shiny new herd name is: Ughghg');
    }
}

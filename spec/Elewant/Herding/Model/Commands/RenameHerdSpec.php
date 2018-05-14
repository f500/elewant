<?php

namespace spec\Elewant\Herding\Model\Commands;

use Elewant\Herding\Model\Commands\RenameHerd;
use Elewant\Herding\Model\HerdId;
use PhpSpec\ObjectBehavior;

class RenameHerdSpec extends ObjectBehavior
{
    function it_is_initializable()
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

    function it_cannot_have_a_herd_name_shorter_than_one_character()
    {
        $this->beConstructedThrough('forShepherd', ['id_does_not_matter', '']);
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_cannot_have_a_herd_name_longer_than_fifty_characters()
    {
        $this->beConstructedThrough('forShepherd', ['id_does_not_matter', '01234567890123456789012345678901234567890123456789!']);
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_cannot_have_a_herd_nane_that_starts_with_a_space()
    {
        $this->beConstructedThrough('forShepherd', ['id_does_not_matter', ' name']);
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }

    function it_cannot_have_a_herd_nane_that_ends_with_a_space()
    {
        $this->beConstructedThrough('forShepherd', ['id_does_not_matter', 'name ']);
        $this->shouldThrow('\InvalidArgumentException')->duringInstantiation();
    }
}

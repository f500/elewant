<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\BreedCollection;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\ShepherdId;
use Elewant\Herding\Model\SorryICanNotChangeHerd;
use Elewant\Herding\Model\SorryIDoNotHaveThat;
use PhpSpec\ObjectBehavior;

class HerdSpec extends ObjectBehavior
{
    /** @var ShepherdId */
    private $shepherdId;

    /** @var  string */
    private $herdName;

    function let()
    {
        $this->shepherdId = ShepherdId::generate();
        $this->herdName   = 'Herd name';
        $this->beConstructedThrough(
            'form',
            [
                $this->shepherdId,
                $this->herdName,
            ]
        );
    }

    function it_forms()
    {
        $this->shouldHaveType(Herd::class);
        $this->shepherdId()->shouldEqual($this->shepherdId);
        $this->name()->shouldEqual($this->herdName);
    }

    function it_adopts_one_new_elephpant()
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);
        $this->elePHPants()->shouldContainAnElePHPant(Breed::blueOriginalRegular());
    }

    function it_adopts_two_new_elephpants()
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->adoptElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldHaveCount(2);
        $this->elePHPants()->shouldContainAnElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldContainAnElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldNotContainAnElePHPant(Breed::redLaravelRegular());
    }

    function it_abandons_one_elephpant()
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->adoptElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldHaveCount(2);

        $this->abandonElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);
        $this->elePHPants()->shouldNotContainAnElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldContainAnElePHPant(Breed::greenZf2Regular());
    }

    function it_throws_an_exception_when_abandoning_without_any_elephpants()
    {
        $this->shouldThrow(SorryIDoNotHaveThat::class)
            ->duringAbandonElePHPant(Breed::greenZf2Regular());
    }

    function it_throws_an_exception_when_abandoning_a_not_owned_elephpant()
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);

        $this->shouldThrow(SorryIDoNotHaveThat::class)
            ->duringAbandonElePHPant(Breed::greenZf2Regular());
    }

    function it_can_be_renamed()
    {
        $this->name()->shouldEqual($this->herdName);
        $this->rename('new name');
        $this->name()->shouldEqual('new name');
    }

    function it_can_be_abandoned()
    {
        $this->shouldHaveType(Herd::class);
        $this->isAbandoned()->shouldReturn(false);
        $this->abandon();
        $this->isAbandoned()->shouldReturn(true);
    }

    function it_cannot_be_abandoned_twice()
    {
        $this->shouldHaveType(Herd::class);
        $this->abandon();

        $this->isAbandoned()->shouldReturn(true);
        $this->shouldThrow(SorryICanNotChangeHerd::class)->during('abandon');
    }

    function it_cannot_be_changed_once_it_is_abandoned()
    {
        $this->shouldHaveType(Herd::class);
        $this->abandon();

        $this->isAbandoned()->shouldReturn(true);
        $this->shouldThrow(SorryICanNotChangeHerd::class)->during('adoptElePHPant', [Breed::blueOriginalRegular()]);
    }

    function it_contains_a_breedcollection()
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->adoptElePHPant(Breed::greenZf2Regular());
        $this->adoptElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldHaveCount(3);
        $this->elePHPants()->shouldContainAnElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldContainAnElePHPant(Breed::greenZf2Regular());

        $this->breeds()->shouldBeAnInstanceOf(BreedCollection::class);
        $this->breeds()->shouldHaveCount(2);
        $this->breeds()->contains(Breed::blueOriginalRegular())->shouldReturn(true);
        $this->breeds()->contains(Breed::greenZf2Regular())->shouldReturn(true);
        $this->breeds()->contains(Breed::redLaravelRegular())->shouldReturn(false);
    }

    public function getMatchers()
    {
        return [
            'containAnElePHPant' => function ($elePHPants, $type) {
                foreach ($elePHPants as $elePHPant) {
                    if ($elePHPant->breed()->equals($type)) {
                        return true;
                    }
                }

                return false;
            },
        ];
    }


}

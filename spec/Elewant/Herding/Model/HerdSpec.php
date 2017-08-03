<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\ShepherdId;
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
        $this->herdName = 'Herd name';
        $this->beConstructedThrough('form', [
            $this->shepherdId,
            $this->herdName
        ]);
    }

    function it_forms()
    {
        $this->shouldHaveType(Herd::class);
        $this->shepherdId()->shouldEqual($this->shepherdId);
        $this->name()->shouldEqual($this->herdName);
    }

    function it_embraces_one_new_elephpant()
    {
        $this->embraceElePHPant(Breed::fromString('blue'));
        $this->elePHPants()->shouldHaveCount(1);
        $this->elePHPants()->shouldContainAnElePHPant(Breed::fromString('blue'));
    }

    function it_embraces_two_new_elephpants()
    {
        $this->embraceElePHPant(Breed::fromString('blue'));
        $this->embraceElePHPant(Breed::fromString('green'));
        $this->elePHPants()->shouldHaveCount(2);
        $this->elePHPants()->shouldContainAnElePHPant(Breed::fromString('blue'));
        $this->elePHPants()->shouldContainAnElePHPant(Breed::fromString('green'));
        $this->elePHPants()->shouldNotContainAnElePHPant(Breed::fromString('red'));
    }

    function it_abandons_one_elephpant()
    {
        $this->embraceElePHPant(Breed::fromString('blue'));
        $this->embraceElePHPant(Breed::fromString('green'));
        $this->elePHPants()->shouldHaveCount(2);

        $this->abandonElePHPant(Breed::fromString('blue'));
        $this->elePHPants()->shouldHaveCount(1);
        $this->elePHPants()->shouldNotContainAnElePHPant(Breed::fromString('blue'));
        $this->elePHPants()->shouldContainAnElePHPant(Breed::fromString('green'));
    }

    function it_throws_an_exception_when_abandoning_without_any_elephpants()
    {
        $this->shouldThrow(SorryIDoNotHaveThat::class)
            ->duringAbandonElePHPant(Breed::fromString('green'));
    }

    function it_throws_an_exception_when_abandoning_a_not_owned_elephpant()
    {
        $this->embraceElePHPant(Breed::fromString('blue'));
        $this->elePHPants()->shouldHaveCount(1);

        $this->shouldThrow(SorryIDoNotHaveThat::class)
            ->duringAbandonElePHPant(Breed::fromString('green'));
    }

    public function getMatchers()
    {
        return [
            'containAnElePHPant' => function ($elePHPants, $type) {
                foreach ($elePHPants as $elePHPant) {
                    if ($elePHPant->type()->equals($type)) {
                        return true;
                    }
                }
                return false;
            }
        ];
    }


}

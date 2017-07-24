<?php

namespace spec\Elewant\Domain;

use Elewant\Domain\Breed;
use Elewant\Domain\Herd;
use Elewant\Domain\ShepherdId;
use Elewant\Domain\SorryIDoNotHaveThat;
use PhpSpec\ObjectBehavior;

class HerdSpec extends ObjectBehavior
{
    /**
     * @var ShepherdId
     */
    private $shepherdId;

    function let()
    {
        $this->shepherdId = ShepherdId::generate();
        $this->beConstructedThrough('form', [$this->shepherdId]);
    }

    function it_forms()
    {
        $this->shouldHaveType(Herd::class);
        $this->shepherdId()->shouldBeEqual($this->shepherdId);
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
            'beEqual' => function ($subject, $other) {
                return $subject->equals($other);
            },
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

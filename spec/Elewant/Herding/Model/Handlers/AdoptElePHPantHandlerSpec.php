<?php

namespace spec\Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Commands\AdoptElePHPant;
use Elewant\Herding\Model\Handlers\AdoptElePHPantHandler;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AdoptElePHPantHandlerSpec extends ObjectBehavior
{
    /** @var HerdCollection */
    private $herdCollection;

    function let(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
        $this->beConstructedWith($herdCollection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AdoptElePHPantHandler::class);
    }

    function it_handles_adopt_elephpant()
    {
        $herd = Herd::form(ShepherdId::fromString('00000000-0000-0000-0000-000000000000'),'Herd name');
        $herdId = $herd->herdId();
        $command = AdoptElePHPant::byHerd($herdId->toString(), Breed::WHITE_DPC_REGULAR);

        $this->herdCollection->get($herdId)->willReturn($herd);

        $this->herdCollection->save(Argument::that(function ($changedHerd) use ($herdId) {
                if (!$changedHerd->herdId()->equals($herdId)) {
                    return false;
                }

                $elePHPants = $changedHerd->elePHPants();
                if (empty($elePHPants)) {
                    return false;
                }

                return $elePHPants[0]->breed() == Breed::whiteDpcRegular();
        }))->shouldBeCalled();

        $this->__invoke($command);
    }
}

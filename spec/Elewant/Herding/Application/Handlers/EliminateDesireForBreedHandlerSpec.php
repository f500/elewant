<?php

namespace spec\Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Application\Commands\EliminateDesireForBreed;
use Elewant\Herding\Model\Events\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\Application\Handlers\EliminateDesireForBreedHandler;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Elewant\Herding\Model\SorryIDoNotHaveThat;
use Elewant\Tooling\PhpSpec\PopAggregateEventsTrait;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webmozart\Assert\Assert;

class EliminateDesireForBreedHandlerSpec extends ObjectBehavior
{

    use PopAggregateEventsTrait;

    /** @var  HerdCollection */
    private $herdCollection;

    function let(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
        $this->beConstructedWith($herdCollection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EliminateDesireForBreedHandler::class);
    }

    function it_handles_eliminate_desire_for_breed()
    {
        $herd   = Herd::form(ShepherdId::fromString('00000000-0000-0000-0000-000000000000'), 'Herd name');
        $herdId = $herd->herdId();
        $herd->desireBreed(Breed::whiteDpcRegular());

        $command = EliminateDesireForBreed::byHerd($herdId->toString(), Breed::WHITE_DPC_REGULAR);

        $this->herdCollection->get($herdId)->willReturn($herd);
        $this->herdCollection->save(Argument::type(Herd::class))->shouldBeCalled();
        $this->__invoke($command);

        $events = $this->popRecordedEvent($herd);

        Assert::count($events, 3);
        Assert::isInstanceOf($events[2], BreedDesireWasEliminatedByHerd::class);

        $payload = $events[2]->payload();
        Assert::same($payload['breed'], Breed::WHITE_DPC_REGULAR);
    }

    function it_throws_an_exception_for_an_unknown_herd()
    {
        $herdId  = HerdId::generate();
        $command = EliminateDesireForBreed::byHerd($herdId->toString(), Breed::WHITE_DPC_REGULAR);

        $this->herdCollection->get($herdId)->willReturn(null);
        $this->shouldThrow(SorryIDoNotHaveThat::herd($herdId))->during('__invoke', [$command]);
    }

}

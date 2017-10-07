<?php

namespace spec\Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Commands\AdoptElePHPant;
use Elewant\Herding\Model\Events\ElePHPantWasAdoptedByHerd;
use Elewant\Herding\Model\Handlers\AdoptElePHPantHandler;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Elewant\Herding\Model\SorryIDoNotHaveThat;
use Elewant\Tooling\PhpSpec\PopAggregateEventsTrait;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webmozart\Assert\Assert;

class AdoptElePHPantHandlerSpec extends ObjectBehavior
{
    use PopAggregateEventsTrait;

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
        $herd    = Herd::form(ShepherdId::fromString('00000000-0000-0000-0000-000000000000'), 'Herd name');
        $herdId  = $herd->herdId();
        $command = AdoptElePHPant::byHerd($herdId->toString(), Breed::WHITE_DPC_REGULAR);

        $this->herdCollection->get($herdId)->willReturn($herd);
        $this->herdCollection->save(Argument::type(Herd::class))->shouldBeCalled();
        $this->__invoke($command);

        $events = $this->popRecordedEvent($herd);
        Assert::count($events, 2);
        Assert::isInstanceOf($events[1], ElePHPantWasAdoptedByHerd::class);

        $payload = $events[1]->payload();
        Assert::same($payload['breed'], Breed::WHITE_DPC_REGULAR);
    }

    function it_throws_an_exception_for_an_unknown_herd()
    {
        $herdId  = HerdId::generate();
        $command = AdoptElePHPant::byHerd($herdId->toString(), Breed::WHITE_DPC_REGULAR);

        $this->herdCollection->get($herdId)->willReturn(null);
        $this->shouldThrow(SorryIDoNotHaveThat::herd($herdId))->during('__invoke', [$command]);
    }

}

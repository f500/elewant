<?php

namespace spec\Elewant\Herding\Model\Handlers;

use Elewant\Herding\Model\Commands\AbandonHerd;
use Elewant\Herding\Model\Events\HerdWasAbandoned;
use Elewant\Herding\Model\Handlers\AbandonHerdHandler;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Elewant\Herding\Model\SorryIDoNotHaveThat;
use Elewant\Tooling\PhpSpec\PopAggregateEventsTrait;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webmozart\Assert\Assert;

class AbandonHerdHandlerSpec extends ObjectBehavior
{
    use PopAggregateEventsTrait;

    /**
     * @var HerdCollection
     */
    private $herdCollection;

    function let(HerdCollection $herdCollection)
    {
        $this->herdCollection = $herdCollection;
        $this->beConstructedWith($herdCollection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AbandonHerdHandler::class);
    }

    function it_handles_abandon_herd()
    {
        $shepherdId = ShepherdId::fromString('00000000-0000-0000-0000-000000000000');
        $herd       = Herd::form($shepherdId, 'Herd name');
        $herdId     = $herd->herdId();

        $command = AbandonHerd::forShepherd($herdId->toString(), $shepherdId->toString());

        $this->herdCollection->get($herdId)->willReturn($herd);
        $this->herdCollection->save(Argument::type(Herd::class))->shouldBeCalled();
        $this->__invoke($command);

        $events = $this->popRecordedEvent($herd);

        Assert::count($events, 2);
        Assert::isInstanceOf($events[1], HerdWasAbandoned::class);

        $payload = $events[1]->payload();
        Assert::same($payload['shepherdId'], $shepherdId->toString());
    }

    function it_throws_an_exception_for_an_unknown_herd()
    {
        $herdId  = HerdId::generate();
        $command = AbandonHerd::forShepherd($herdId->toString(), ShepherdId::generate()->toString());

        $this->herdCollection->get($herdId)->willReturn(null);
        $this->shouldThrow(SorryIDoNotHaveThat::herd($herdId))->during('__invoke', [$command]);
    }

}

<?php

namespace spec\Elewant\Herding\Model\Handlers;

use Elewant\Herding\Application\Commands\RenameHerd;
use Elewant\Herding\Application\Handlers\RenameHerdHandler;
use Elewant\Herding\Model\Events\HerdWasRenamed;
use Elewant\Herding\Model\Herd;
use Elewant\Herding\Model\HerdCollection;
use Elewant\Herding\Model\HerdId;
use Elewant\Herding\Model\ShepherdId;
use Elewant\Herding\Model\SorryIDoNotHaveThat;
use Elewant\Tooling\PhpSpec\popAggregateEventsTrait;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Webmozart\Assert\Assert;

class RenameHerdHandlerSpec extends ObjectBehavior
{
    use popAggregateEventsTrait;

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
        $this->shouldHaveType(RenameHerdHandler::class);
    }

    function it_renames_a_herd()
    {
        $shepherdId = ShepherdId::fromString('00000000-0000-0000-0000-000000000000');
        $herd       = Herd::form($shepherdId, 'Herd name');
        $herdId     = $herd->herdId();

        $command = RenameHerd::forShepherd($herdId->toString(), 'newHerdName');

        $this->herdCollection->get($herdId)->willReturn($herd);
        $this->herdCollection->save(Argument::type(Herd::class))->shouldBeCalled();
        $this->__invoke($command);

        $events = $this->popRecordedEvent($herd);

        Assert::count($events, 2);
        Assert::isInstanceOf($events[1], HerdWasRenamed::class);

        $payload = $events[1]->payload();
        Assert::same($payload['newHerdName'], 'newHerdName');
        Assert::same($herd->name(), 'newHerdName');

    }

    function it_throws_an_exception_for_an_unknown_herd()
    {
        $herdId  = HerdId::generate();
        $command = RenameHerd::forShepherd($herdId->toString(), 'unused');

        $this->herdCollection->get($herdId)->willReturn(null);
        $this->shouldThrow(SorryIDoNotHaveThat::herd($herdId))->during('__invoke', [$command]);
    }

}

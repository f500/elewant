<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Herd\Herd;
use Elewant\Herding\DomainModel\Herd\HerdCollection;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\Herd\HerdWasAbandoned;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Herding\DomainModel\SorryIDoNotHaveThat;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tooling\PhpSpec\PopAggregateEventsTrait;
use Webmozart\Assert\Assert;

final class AbandonHerdHandlerSpec extends ObjectBehavior
{
    use PopAggregateEventsTrait;

    /**
     * @var HerdCollection
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    private $herdCollection;

    public function let(HerdCollection $herdCollection): void
    {
        $this->herdCollection = $herdCollection;
        $this->beConstructedWith($herdCollection);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(AbandonHerdHandler::class);
    }

    public function it_handles_abandon_herd(): void
    {
        $shepherdId = ShepherdId::fromString('00000000-0000-0000-0000-000000000000');
        $herd = Herd::form($shepherdId, 'Herd name');
        $herdId = $herd->herdId();

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

    public function it_throws_an_exception_for_an_unknown_herd(): void
    {
        $herdId = HerdId::generate();
        $command = AbandonHerd::forShepherd($herdId->toString(), ShepherdId::generate()->toString());

        $this->herdCollection->get($herdId)->willReturn(null);
        $this->shouldThrow(SorryIDoNotHaveThat::herd($herdId))->during('__invoke', [$command]);
    }
}

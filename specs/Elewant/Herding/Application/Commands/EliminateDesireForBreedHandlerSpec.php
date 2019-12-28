<?php

declare(strict_types=1);

namespace Elewant\Herding\Application\Commands;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Breed\BreedDesireWasEliminatedByHerd;
use Elewant\Herding\DomainModel\Herd\Herd;
use Elewant\Herding\DomainModel\Herd\HerdCollection;
use Elewant\Herding\DomainModel\Herd\HerdId;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Herding\DomainModel\SorryIDoNotHaveThat;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tooling\PhpSpec\PopAggregateEventsTrait;
use Webmozart\Assert\Assert;

final class EliminateDesireForBreedHandlerSpec extends ObjectBehavior
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
        $this->shouldHaveType(EliminateDesireForBreedHandler::class);
    }

    public function it_handles_eliminate_desire_for_breed(): void
    {
        $herd = Herd::form(ShepherdId::fromString('00000000-0000-0000-0000-000000000000'), 'Herd name');
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

    public function it_throws_an_exception_for_an_unknown_herd(): void
    {
        $herdId = HerdId::generate();
        $command = EliminateDesireForBreed::byHerd($herdId->toString(), Breed::WHITE_DPC_REGULAR);

        $this->herdCollection->get($herdId)->willReturn(null);
        $this->shouldThrow(SorryIDoNotHaveThat::herd($herdId))->during('__invoke', [$command]);
    }
}

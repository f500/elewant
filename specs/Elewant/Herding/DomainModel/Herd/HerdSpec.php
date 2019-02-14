<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Elewant\Herding\DomainModel\Breed\Breed;
use Elewant\Herding\DomainModel\Breed\BreedCollection;
use Elewant\Herding\DomainModel\ElePHPant\ElePHPant;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Herding\DomainModel\SorryICanNotChangeHerd;
use Elewant\Herding\DomainModel\SorryIDoNotHaveThat;
use PhpSpec\ObjectBehavior;

final class HerdSpec extends ObjectBehavior
{
    /**
     * @var ShepherdId
     */
    private $shepherdId;

    /**
     * @var string
     */
    private $herdName;

    public function let(): void
    {
        $this->shepherdId = ShepherdId::generate();
        $this->herdName = 'Herd name';
        $this->beConstructedThrough(
            'form',
            [
                $this->shepherdId,
                $this->herdName,
            ]
        );
    }

    public function it_forms(): void
    {
        $this->shouldHaveType(Herd::class);
        $this->shepherdId()->shouldEqual($this->shepherdId);
        $this->name()->shouldEqual($this->herdName);
    }

    public function it_adopts_one_new_elephpant(): void
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);
        $this->elePHPants()->shouldContainAnElePHPant(Breed::blueOriginalRegular());
    }

    public function it_ignores_adoption_of_an_unknown_breed(): void
    {
        $this->adoptElePHPant(Breed::fromString('UNKNOWN_BREED'));
        $this->elePHPants()->shouldHaveCount(0);
    }

    public function it_adopts_two_new_elephpants(): void
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->adoptElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldHaveCount(2);
        $this->elePHPants()->shouldContainAnElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldContainAnElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldNotContainAnElePHPant(Breed::redLaravelRegular());
    }

    public function it_abandons_one_elephpant(): void
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->adoptElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldHaveCount(2);

        $this->abandonElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);
        $this->elePHPants()->shouldNotContainAnElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldContainAnElePHPant(Breed::greenZf2Regular());
    }

    public function it_ignores_abandonment_of_an_unknown_breed(): void
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);

        $this->abandonElePHPant(Breed::fromString('UNKNOWN_BREED'));
        $this->elePHPants()->shouldHaveCount(1);
    }

    public function it_abandons_the_same_breed_twice(): void
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->adoptElePHPant(Breed::greenZf2Regular());
        $this->elePHPants()->shouldHaveCount(3);

        $this->abandonElePHPant(Breed::blueOriginalRegular());
        $this->abandonElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);
        $this->elePHPants()->shouldNotContainAnElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldContainAnElePHPant(Breed::greenZf2Regular());
    }

    public function it_throws_an_exception_when_abandoning_without_any_elephpants(): void
    {
        $this->shouldThrow(SorryIDoNotHaveThat::class)
            ->duringAbandonElePHPant(Breed::greenZf2Regular());
    }

    public function it_throws_an_exception_when_abandoning_a_not_owned_elephpant(): void
    {
        $this->adoptElePHPant(Breed::blueOriginalRegular());
        $this->elePHPants()->shouldHaveCount(1);

        $this->shouldThrow(SorryIDoNotHaveThat::class)
            ->duringAbandonElePHPant(Breed::greenZf2Regular());
    }

    public function it_can_be_renamed(): void
    {
        $this->name()->shouldEqual($this->herdName);
        $this->rename('new name');
        $this->name()->shouldEqual('new name');
    }

    public function it_can_be_abandoned(): void
    {
        $this->shouldHaveType(Herd::class);
        $this->isAbandoned()->shouldReturn(false);
        $this->abandon();
        $this->isAbandoned()->shouldReturn(true);
    }

    public function it_cannot_be_abandoned_twice(): void
    {
        $this->shouldHaveType(Herd::class);
        $this->abandon();

        $this->isAbandoned()->shouldReturn(true);
        $this->shouldThrow(SorryICanNotChangeHerd::class)->during('abandon');
    }

    public function it_cannot_be_changed_once_it_is_abandoned(): void
    {
        $this->shouldHaveType(Herd::class);
        $this->abandon();

        $this->isAbandoned()->shouldReturn(true);
        $this->shouldThrow(SorryICanNotChangeHerd::class)->during('adoptElePHPant', [Breed::blueOriginalRegular()]);
    }

    public function it_contains_a_breedcollection(): void
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

    public function it_contains_a_desired_breedcollection(): void
    {
        $this->desiredBreeds()->shouldHaveType(BreedCollection::class);
    }

    public function it_can_desire_a_new_breed(): void
    {
        $this->desireBreed(Breed::blueOriginalRegular());
        $this->desireBreed(Breed::greenZf2Regular());

        $this->desiredBreeds()->shouldHaveCount(2);
        $this->desiredBreeds()->contains(Breed::blueOriginalRegular())->shouldReturn(true);
        $this->desiredBreeds()->contains(Breed::greenZf2Regular())->shouldReturn(true);
        $this->desiredBreeds()->contains(Breed::redLaravelRegular())->shouldReturn(false);
    }

    public function it_can_desire_a_new_breed_multiple_times(): void
    {
        $this->desireBreed(Breed::blueOriginalRegular());
        $this->desireBreed(Breed::blueOriginalRegular());
        $this->desireBreed(Breed::blueOriginalRegular());

        $this->desiredBreeds()->shouldHaveCount(1);
        $this->desiredBreeds()->contains(Breed::blueOriginalRegular())->shouldReturn(true);
    }

    public function it_ignores_desires_for_an_unknown_breed(): void
    {
        $this->desireBreed(Breed::fromString('UNKNOWN_BREED'));
        $this->desiredBreeds()->shouldHaveCount(0);
    }

    public function it_can_eliminate_the_desire_for_a_new_breed(): void
    {
        $this->desireBreed(Breed::greenZf2Regular());
        $this->desiredBreeds()->shouldHaveCount(1);

        $this->eliminateDesireForBreed(Breed::greenZf2Regular());
        $this->desiredBreeds()->shouldHaveCount(0);
    }

    public function it_can_eliminate_the_desire_for_a_breed_it_did_not_desire(): void
    {
        $this->eliminateDesireForBreed(Breed::greenZf2Regular());
        $this->eliminateDesireForBreed(Breed::greenZf2Regular());
        $this->desiredBreeds()->shouldHaveCount(0);
    }

    /**
     * @return callable[]
     */
    public function getMatchers(): array
    {
        return [
            'containAnElePHPant' => static function (array $elePHPants, Breed $breed): bool {
                /** @var ElePHPant $elePHPant */
                foreach ($elePHPants as $elePHPant) {
                    if ($elePHPant->breed()->equals($breed)) {
                        return true;
                    }
                }

                return false;
            },
        ];
    }
}

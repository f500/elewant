<?php

namespace spec\Elewant\Herding\Model;

use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\BreedCollection;
use PhpSpec\ObjectBehavior;

class BreedCollectionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromArray', [[]]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BreedCollection::class);
    }

    function it_is_empty_without_breeds()
    {
        $this->isEmpty()->shouldReturn(true);
    }

    function it_is_not_empty_with_a_breed()
    {
        $this->add(Breed::fromString(Breed::WHITE_CONFOO_LARGE));
        $this->isEmpty()->shouldReturn(false);
    }

    function it_returns_breeds()
    {
        $confoo       = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $this->add($confoo);
        $this->add($amsterdamPHP);

        $expected = [
            $confoo,
            $amsterdamPHP,
        ];

        $this->breeds()->shouldReturn($expected);
    }

    function it_does_not_add_the_same_breed_more_than_once()
    {
        $confoo = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $this->add($confoo);
        $this->add($confoo);
        $this->add($confoo);

        $expected = [
            $confoo,
        ];

        $this->breeds()->shouldReturn($expected);
    }

    function it_equals_another_empty_breedcollection()
    {
        $other = BreedCollection::fromArray([]);
        $this->equals($other)->shouldReturn(true);
    }

    function it_equals_another_similar_breedcollection()
    {
        $other = BreedCollection::fromArray(
            [
                Breed::fromString(Breed::WHITE_CONFOO_LARGE),
                Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR),
            ]
        );

        $this->add(Breed::fromString(Breed::WHITE_CONFOO_LARGE));
        $this->add(Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR));

        $this->equals($other)->shouldReturn(true);
    }

    function it_does_not_equal_a_different_breedcollection()
    {
        $other = BreedCollection::fromArray(
            [
                Breed::fromString(Breed::WHITE_CONFOO_LARGE),
            ]
        );

        $this->add(Breed::fromString(Breed::WHITE_CONFOO_LARGE));
        $this->add(Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR));

        $this->equals($other)->shouldReturn(false);
    }

    function it_merges_with_another_breedcollection()
    {
        $confoo       = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2          = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $other = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2
            ]
        );

        $expected = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2
            ]
        );

        $this->add($confoo);
        $this->merge($other);
        $this->equals($expected)->shouldReturn(true);
    }

    function it_diffs_with_another_breedcollection()
    {
        $confoo       = Breed::fromString(Breed::WHITE_CONFOO_LARGE);
        $amsterdamPHP = Breed::fromString(Breed::BLACK_AMSTERDAMPHP_REGULAR);
        $zf2          = Breed::fromString(Breed::GREEN_ZF2_LARGE);

        $other = BreedCollection::fromArray(
            [
                $confoo,
                $amsterdamPHP,
                $zf2
            ]
        );

        $expected = BreedCollection::fromArray(
            [
                $amsterdamPHP,
                $zf2
            ]
        );

        $this->add($confoo);
        $actualCollection = $this->diff($other);
        $actualCollection->equals($expected)->shouldReturn(true);
    }

}

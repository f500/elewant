<?php

declare(strict_types=1);

namespace Elewant\Herding\Model;

final class BreedCollection
{
    /**
     * @var Breed[]
     */
    private $breeds = [];

    private function __construct()
    {
    }

    public static function fromArray(array $breeds): self
    {
        $collection = new self();
        foreach ($breeds as $breed) {
            $collection->add($breed);
        }

        return $collection;
    }

    public static function all(): self
    {
        $collection = new self();
        foreach (Breed::availableTypes() as $type) {
            $collection->add(Breed::fromString($type));
        }

        return $collection;
    }

    public function isEmpty(): bool
    {
        return count($this->breeds) === 0;
    }

    public function add(Breed $breed): void
    {
        if (in_array($breed, $this->breeds)) {
            return;
        }

        $this->breeds[] = $breed;
    }

    public function remove(Breed $breed): void
    {
        if (!in_array($breed, $this->breeds)) {
            return;
        }

        $this->breeds = array_diff($this->breeds, [$breed]);
    }

    public function breeds(): array
    {
        return $this->breeds;
    }

    public function equals(BreedCollection $otherCollection): bool
    {
        $diff = array_diff($this->breeds, $otherCollection->breeds);

        return empty($diff) && count($this->breeds) === count($otherCollection->breeds);
    }

    public function merge(BreedCollection $otherCollection): void
    {
        array_map(
            function (Breed $breed) {
                $this->add($breed);
            },
            $otherCollection->breeds
        );
    }

    public function isMissingBreedsWhenComparedTo(BreedCollection $otherCollection): self
    {
        $newBreeds = array_diff($otherCollection->breeds, $this->breeds);

        return BreedCollection::fromArray($newBreeds);
    }

    public function hasBreedsInCommonWith(BreedCollection $otherCollection): self
    {
        $newBreeds = array_intersect($otherCollection->breeds, $this->breeds);

        return BreedCollection::fromArray($newBreeds);
    }


    public function contains(Breed $breed): bool
    {
        return in_array($breed, $this->breeds);
    }
}

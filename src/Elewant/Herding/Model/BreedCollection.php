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

    public function isEmpty(): bool
    {
        return count($this->breeds) === 0;
    }

    public function add(Breed $breed)
    {
        if (in_array($breed, $this->breeds)) {
            return;
        }

        $this->breeds[] = $breed;
    }

    public function breeds()
    {
        return $this->breeds;
    }

    public function equals(BreedCollection $otherCollection)
    {
        $diff = array_diff($this->breeds, $otherCollection->breeds);

        return empty($diff) && count($this->breeds) === count($otherCollection->breeds);
    }

    public function merge(BreedCollection $otherCollection)
    {
        array_map(
            function (Breed $breed) {
                $this->add($breed);
            },
            $otherCollection->breeds
        );
    }

    public function diff(BreedCollection $otherCollection)
    {
        $newBreeds = array_diff($otherCollection->breeds, $this->breeds);

        return BreedCollection::fromArray($newBreeds);
    }
}

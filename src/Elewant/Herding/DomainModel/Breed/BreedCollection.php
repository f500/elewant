<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Breed;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final class BreedCollection implements Countable, IteratorAggregate
{
    /**
     * @var Breed[]
     */
    private $breeds = [];

    private function __construct()
    {
        // noop
    }

    /**
     * @param Breed[] $breeds
     * @return BreedCollection
     */
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
            /** @noinspection PhpUnhandledExceptionInspection */
            $collection->add(Breed::fromString($type));
        }

        return $collection;
    }

    public static function allRegular(): self
    {
        $collection = new self();

        foreach (Breed::availableTypes() as $type) {
            if (strstr($type, 'REGULAR') === false) {
                continue;
            }

            /** @noinspection PhpUnhandledExceptionInspection */
            $collection->add(Breed::fromString($type));
        }

        return $collection;
    }

    public static function allLarge(): self
    {
        return self::allRegular()->isMissingBreedsWhenComparedTo(self::all());
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

    /**
     * @return Breed[]
     */
    public function breeds(): array
    {
        return $this->breeds;
    }

    public function equals(BreedCollection $otherCollection): bool
    {
        $diff = array_diff($this->breeds, $otherCollection->breeds);

        return !$diff && count($this->breeds) === count($otherCollection->breeds);
    }

    public function merge(BreedCollection $otherCollection): void
    {
        array_map(
            function (Breed $breed): void {
                $this->add($breed);
            },
            $otherCollection->breeds
        );
    }

    public function isMissingBreedsWhenComparedTo(BreedCollection $otherCollection): self
    {
        $newBreeds = array_diff($otherCollection->breeds, $this->breeds);

        return self::fromArray($newBreeds);
    }

    public function hasBreedsInCommonWith(BreedCollection $otherCollection): self
    {
        $newBreeds = array_intersect($otherCollection->breeds, $this->breeds);

        return self::fromArray($newBreeds);
    }

    public function contains(Breed $breed): bool
    {
        return in_array($breed, $this->breeds);
    }

    public function count(): int
    {
        return count($this->breeds);
    }

    /**
     * @return Breed[]|Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->breeds);
    }
}

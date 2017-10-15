<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\BreedCollection;

/**
 * @ORM\Entity(repositoryClass="Elewant\AppBundle\Repository\HerdRepository")
 * @ORM\Table(indexes={@ORM\Index(columns={"formed_on"}),@ORM\Index(columns={"shepherd_id"})})
 */
class Herd
{
    /**
     * @ORM\Column(type="guid")
     * @var string
     */
    private $shepherdId;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $formedOn;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @var string
     */
    private $herdId;

    /**
     * @ORM\OneToMany(targetEntity="Elewant\AppBundle\Entity\ElePHPant", mappedBy="herd", cascade={"persist"})
     * @var ArrayCollection
     */
    private $elePHPants;

    private function __construct()
    {
        $this->elePHPants = new ArrayCollection();
    }

    public function shepherdId(): string
    {
        return $this->shepherdId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function formedOn(): \Datetime
    {
        return $this->formedOn;
    }

    public function herdId(): string
    {
        return $this->herdId;
    }

    public function elePHPants(): Collection
    {
        return $this->elePHPants;
    }

    public function elePHPantBreeds(): BreedCollection
    {
        $collection = BreedCollection::fromArray([]);
        foreach ($this->elePHPants as $elePHPant) {
            $collection->add($elePHPant->breed());
        }

        return $collection;
    }

    public function filteredByBreed(Breed $breed): Collection
    {
        $filtered = $this->elePHPants->filter(
            function ($elephpant) use ($breed) {
                return $elephpant->breed()->equals($breed);
            }
        );

        return $filtered;
    }
}

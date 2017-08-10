<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Herd
 * @ORM\Entity(repositoryClass="Elewant\FrontendBundle\Repository\HerdRepository")
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
     * @ORM\OneToMany(targetEntity="Elewant\FrontendBundle\Entity\Elephpant", mappedBy="herd", cascade={"persist"})
     * @var ArrayCollection
     */
    private $elephpants;

    private function __construct()
    {
        $this->elephpants = new ArrayCollection();
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
        return $this->elephpants;
    }

    public function elePHPantBreedCounts(): Collection
    {
        $result = [];
        foreach ($this->elephpants as $elephpant) {
            $breed = $elephpant->breed()->toString();
            if (!isset($result[$breed])) {
                $result[$breed] = 0;
            }
            $result[$breed]++;
        }

        return new ArrayCollection($result);
    }


}


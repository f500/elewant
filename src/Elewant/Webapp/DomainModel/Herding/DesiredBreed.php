<?php

declare(strict_types=1);

namespace Elewant\Webapp\DomainModel\Herding;

/**
 * @todo Is it ok to use Herding\DomainModel here?
 */

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Elewant\Herding\DomainModel\Breed\Breed;

/**
 * @ORM\Entity
 * @ORM\Table(name="desired_breed", indexes={@ORM\Index(columns={"desired_on"})})
 * This entity has a companion proxy, therefor is not final.
 */
class DesiredBreed
{
    /**
     * @ORM\ManyToOne(targetEntity="Herd", inversedBy="desiredBreeds")
     * @ORM\JoinColumn(name="herd_id", referencedColumnName="herd_id", nullable=false)
     * @ORM\Id
     * @var Herd
     */
    private $herd;

    /**
     * @ORM\Column(type="breed", length=64)
     * @ORM\Id
     * @var Breed
     */
    private $breed;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $desiredOn;

    public function herd(): Herd
    {
        return $this->herd;
    }

    public function breed(): Breed
    {
        return $this->breed;
    }

    public function desiredOn(): DateTime
    {
        return $this->desiredOn;
    }
}

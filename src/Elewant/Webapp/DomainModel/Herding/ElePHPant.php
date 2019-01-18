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
 * @ORM\Table(name="elephpant", indexes={@ORM\Index(columns={"adopted_on"})})
 * This entity has a companion proxy, therefor is not final.
 */
class ElePHPant
{
    /**
     * @ORM\ManyToOne(targetEntity="Herd", inversedBy="elePHPants")
     * @ORM\JoinColumn(name="herd_id", referencedColumnName="herd_id", nullable=false)
     * @var Herd
     */
    private $herd;

    /**
     * @ORM\Column(type="breed", length=64)
     * @var Breed
     */
    private $breed;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $adoptedOn;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     * @var string
     */
    private $elephpantId;

    public function herd(): Herd
    {
        return $this->herd;
    }

    public function breed(): Breed
    {
        return $this->breed;
    }

    public function adoptedOn(): DateTime
    {
        return $this->adoptedOn;
    }

    public function elePHPantId(): string
    {
        return $this->elephpantId;
    }
}

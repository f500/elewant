<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Elewant\Herding\Model\Breed;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(columns={"adopted_on"})})
 */
class Elephpant
{
    /**
     * @ORM\ManyToOne(targetEntity="Elewant\AppBundle\Entity\Herd", inversedBy="elephpants")
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
     * @var \DateTime
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

    public function adoptedOn(): \Datetime
    {
        return $this->adoptedOn;
    }

    public function elePHPantId(): string
    {
        return $this->elephpantId;
    }
}


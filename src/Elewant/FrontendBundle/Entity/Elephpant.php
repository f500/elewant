<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Elewant\Herding\Model\Breed;

/**
 * @ORM\Entity
 * @ORM\Table(name="elephpant")
 */
class Elephpant
{
    /**
     * @ORM\ManyToOne(targetEntity="Elewant\FrontendBundle\Entity\Herd", inversedBy="elephpants")
     * @ORM\JoinColumn(name="herd_id", referencedColumnName="herd_id", nullable=false)
     * @var Herd
     */
    private $herd;

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @var string
     */
    private $herdId;

    /**
     * @ORM\Column(type="breed")
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

    public function getHerdId() : string
    {
        return $this->herdId;
    }
}


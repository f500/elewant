<?php

namespace Elewant\FrontendBundle\Entity;

use Elewant\FrontendBundle\Domain\ElephpantState;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="elephpant")
 */
final class Elephpant
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @Orm\ManyToOne(targetEntity="Herd", inversedBy="elephpant")
     * @Orm\JoinColumn(name="herd_id", referencedColumnName="id", nullable=false)
     *
     * @var Herd
     */
    private $herd;

    /**
     * @Orm\ManyToOne(targetEntity="Breed")
     * @Orm\JoinColumn(name="breed_id", referencedColumnName="id", nullable=false)
     *
     * @var Breed
     */
    private $breed;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Herd
     */
    public function getHerd()
    {
        return $this->herd;
    }

    /**
     * @param Herd $herd
     */
    public function setHerd($herd)
    {
        $this->herd = $herd;
    }

    /**
     * @return Breed
     */
    public function getBreed()
    {
        return $this->breed;
    }

    /**
     * @param Breed $breed
     */
    public function setBreed($breed)
    {
        $this->breed = $breed;
    }
}

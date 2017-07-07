<?php

namespace Elewant\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="herd")
 */
final class Herd
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
     * @ORM\OneToMany(targetEntity="Elephpant", mappedBy="herd")
     *
     * @var Elephpant[]
     */
    private $elephpant;

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
     * @return Elephpant[]
     */
    public function getElephpant()
    {
        return $this->elephpant;
    }

    /**
     * @param Elephpant[] $elephpant
     */
    public function setElephpant($elephpant)
    {
        $this->elephpant = $elephpant;
    }
}

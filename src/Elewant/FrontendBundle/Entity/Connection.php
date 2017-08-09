<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="resource_idx", columns={"resource", "resource_id"})})
 *
 * We cannot use `final` here, because of Doctrine proxies.
 */
class Connection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @var int|null
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @var string
     */
    private $resource;

    /**
     * @ORM\Column(type="string", length=128)
     * @var string
     */
    private $resourceId;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $accessToken;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $refreshToken;

    /**
     * @ORM\ManyToOne(targetEntity="Elewant\FrontendBundle\Entity\User", inversedBy="connections")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @var User
     */
    private $user;

    public function __construct(
        User $user,
        string $resource,
        string $resourceId,
        string $accessToken,
        string $refreshToken
    ) {
        $this->user         = $user;
        $this->resource     = $resource;
        $this->resourceId   = $resourceId;
        $this->accessToken  = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function id() : ?int
    {
        return $this->id;
    }

    public function resource() : string
    {
        return $this->resource;
    }

    public function resourceId() : string
    {
        return $this->resourceId;
    }

    public function accessToken() : string
    {
        return $this->accessToken;
    }

    public function refreshToken() : string
    {
        return $this->refreshToken;
    }
}

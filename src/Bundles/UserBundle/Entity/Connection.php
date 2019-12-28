<?php

declare(strict_types=1);

namespace Bundles\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * We cannot use `final` here, because of Doctrine proxies.
 *
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="resource_idx", columns={"resource", "resource_id"})})
 */
class Connection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private string $resource;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private string $resourceId;

    /**
     * @ORM\Column(type="string")
     */
    private string $accessToken;

    /**
     * @ORM\Column(type="string")
     */
    private string $refreshToken;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="connections")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    public function __construct(
        User $user,
        string $resource,
        string $resourceId,
        string $accessToken,
        string $refreshToken
    )
    {
        $this->user = $user;
        $this->resource = $resource;
        $this->resourceId = $resourceId;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function resource(): string
    {
        return $this->resource;
    }

    public function resourceId(): string
    {
        return $this->resourceId;
    }

    public function accessToken(): string
    {
        return $this->accessToken;
    }

    public function refreshToken(): string
    {
        return $this->refreshToken;
    }
}

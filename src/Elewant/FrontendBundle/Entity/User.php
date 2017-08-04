<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="Elewant\FrontendBundle\Repository\UserRepository")
 * @ORM\Table(options={"charset"="utf8mb4", "collate"="utf8mb4_unicode_ci"})
 * @Assert\UniqueEntity("username")
 *
 * We cannot use `final` here, because of Doctrine proxies.
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @var int|null
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $displayName;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="Elewant\FrontendBundle\Entity\Connection", mappedBy="user", cascade={"persist"})
     * @var ArrayCollection
     */
    private $connections;

    public static function register(string $username, string $displayName, string $country) : User
    {
        $user = new self();

        $user->username    = $username;
        $user->displayName = $displayName;
        $user->country     = $country;

        return $user;
    }

    /**
     * Constructor is public for SymfonyForm.
     * @todo: Discus what to do with this. This still allows anemic entities.
     */
    public function __construct()
    {
        $this->connections = new ArrayCollection();
    }

    public function changeDisplayName(string $displayName) : void
    {
        $this->displayName = $displayName;
    }

    public function changeCountry(string $country) : void
    {
        $this->country = $country;
    }

    public function connect(string $resource, string $resourceId, string $accessToken, string $refreshToken) : void
    {
        if ($this->hasConnect($resource)) {
            throw new \LogicException(
                sprintf('Resource "%s" already connected to user "%d"', $resource, $this->id)
            );
        }

        $this->connections->add(new Connection($this, $resource, $resourceId, $accessToken, $refreshToken));
    }

    public function id() : ?int
    {
        return $this->id;
    }

    public function username() : string
    {
        return $this->username;
    }

    public function displayName() : string
    {
        return $this->displayName;
    }

    public function country() : string
    {
        return $this->country;
    }

    /**
     * @return Connection[]
     */
    public function connections() : array
    {
        return $this->connections->toArray();
    }

    /**
     * @return string[]
     */
    public function getRoles() : array
    {
        return ['ROLE_USER'];
    }

    public function getUsername() : string
    {
        return '';
    }

    public function getPassword() : string
    {
        return '';
    }

    public function getSalt() : ?string
    {
        return null;
    }

    public function eraseCredentials() : void
    {
    }

    private function hasConnect(string $resource) : bool
    {
        foreach ($this->connections as $connection) {
            if ($connection->resource() === $resource) {
                return true;
            }
        }

        return false;
    }

    /**
     * We only save the `id` and `username`.
     */
    public function serialize() : string
    {
        return json_encode(
            [
                'id'       => $this->id,
                'username' => $this->username,
            ]
        );
    }

    /**
     * We only save the `id` and `username`.
     * The user-provider will refresh the user (to make it complete).
     *
     * @param string $serialized
     */
    public function unserialize($serialized) : void
    {
        $data = json_decode($serialized, true);

        $this->id       = $data['id'] ?? 0;
        $this->username = $data['username'] ?? '';
    }
}

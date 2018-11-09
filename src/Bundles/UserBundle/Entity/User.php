<?php

declare(strict_types=1);

namespace Bundles\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Elewant\Herding\DomainModel\ShepherdId;
use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;
use InvalidArgumentException;
use LogicException;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Bundles\UserBundle\Repository\UserRepository")
 * @ORM\Table
 * @UniqueEntity("username")
 *
 * We cannot use `final` here, because of Doctrine proxies.
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @var int|null
     */
    private $id;

    /**
     * @ORM\Column(type="shepherd_id", unique=true)
     * @var ShepherdId
     */
    private $shepherdId;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\NotBlank
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    private $displayName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     * @var string
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="Connection", mappedBy="user", cascade={"persist"})
     * @var ArrayCollection
     */
    private $connections;

    public function __construct(string $username, string $displayName, string $country)
    {
        $this->shepherdId  = ShepherdId::generate();
        $this->connections = new ArrayCollection();
        $this->username    = $username;
        $this->displayName = $displayName;
        $this->country     = $country;
        $this->connections = new ArrayCollection();
    }

    public function changeDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function changeCountry(string $country): void
    {
        $this->country = $country;
    }

    public function connect(string $resource, string $resourceId, string $accessToken, string $refreshToken): void
    {
        if ($this->hasConnection($resource)) {
            throw new LogicException(
                sprintf('Resource "%s" already connected to user "%d"', $resource, $this->id)
            );
        }

        $this->connections->add(new Connection($this, $resource, $resourceId, $accessToken, $refreshToken));
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function shepherdId(): ShepherdId
    {
        return $this->shepherdId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function displayName(): string
    {
        return $this->displayName;
    }

    public function country(): string
    {
        return $this->country;
    }

    /**
     * @return Connection[]
     */
    public function connections(): array
    {
        return $this->connections->toArray();
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUsername(): string
    {
        return $this->username();
    }

    public function getPassword(): string
    {
        return '';
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    private function hasConnection(string $resource): bool
    {
        foreach ($this->connections as $connection) {
            if ($connection->resource() === $resource) {
                return true;
            }
        }

        return false;
    }

    /**
     * We don't use associations, so we can safely store the user in sessions.
     * The user-provider will refresh the user, to make it complete and managed.
     */
    public function serialize(): string
    {
        return serialize(
            [
                'id'          => $this->id,
                'shepherdId'  => $this->shepherdId->toString(),
                'username'    => $this->username,
                'displayName' => $this->displayName,
                'country'     => $this->country,
            ]
        );
    }

    /**
     * We don't use associations, so we can safely store the user in sessions.
     * The user-provider will refresh the user, to make it complete and managed.
     *
     * @param $serialized
     *
     * @throws SorryThatIsAnInvalid
     */
    public function unserialize($serialized): void
    {
        $data = unserialize($serialized);

        if (!isset($data['id'])
            || !isset($data['shepherdId'])
            || !isset($data['username'])
            || !isset($data['displayName'])
            || !isset($data['country'])
        ) {
            throw new InvalidArgumentException('Corrupt serialized user: ' . $serialized);
        }

        $this->id          = $data['id'];
        $this->shepherdId  = ShepherdId::fromString($data['shepherdId']);
        $this->username    = $data['username'];
        $this->displayName = $data['displayName'];
        $this->country     = $data['country'];
    }
}

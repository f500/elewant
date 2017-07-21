<?php

namespace Elewant\FrontendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Elewant\FrontendBundle\Repository\AuthenticationRepository")
 * @UniqueEntity("username")
 */
final class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $displayname;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="UserConnect", mappedBy="user", cascade={"persist"})
     * @var UserConnect[]|ArrayCollection
     */
    private $connects;

    /**
     * @param string $displayname
     * @param string $username
     *
     * @return User
     */
    public static function create($displayname, $username)
    {
        $user = new self();

        $user->displayname = (string)$displayname;
        $user->username    = (string)$username;

        return $user;
    }

    /**
     * @return User
     */
    public static function createFormInstance()
    {
        $user = new self();

        return $user;
    }

    /**
     * @param string $displayname
     * @param string $username
     */
    public function createFromForm($displayname, $username)
    {
        $this->displayname = $displayname;
        $this->username    = $username;
    }

    /**
     * @param string $resource
     * @param string $resourceId
     * @param string $token
     */
    public function connect($resource, $resourceId, $token)
    {
        if ($this->hasConnect($resource)) {
            throw new \LogicException(
                sprintf('Resource "%s" already connected to user "%s"', $resource, $this->id)
            );
        }

        $this->connects->add(UserConnect::create($this, $resource, $resourceId, $token));
    }

    /**
     * @param string $country
     */
    public function updateCountry($country)
    {
        $this->country = (string)$country;
    }

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function displayname()
    {
        return $this->displayname;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * @return UserConnect[]|ArrayCollection
     */
    public function connects()
    {
        return $this->connects;
    }

    /**
     * @return string
     */
    public function country()
    {
        return $this->country;
    }

    /**
     * @param string $resource
     *
     * @return bool
     */
    private function hasConnect($resource)
    {
        foreach ($this->connects as $connect) {
            if ($connect->resource() === (string)$resource) {
                return true;
            }
        }

        return false;
    }

    /**
     * Constructor is public for SymfonyForm
     */
    public function __construct()
    {
        $this->connects = new ArrayCollection();
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }
}

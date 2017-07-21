<?php

namespace Elewant\FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_connect")
 */
final class UserConnect implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $resource;

    /**
     * @ORM\Column(type="integer")
     * @var string
     */
    private $resourceId;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="connects", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @param User    $user
     * @param string  $resource
     * @param integer $resourceId
     * @param string  $token
     *
     * @return UserConnect
     */
    public static function create(User $user, $resource, $resourceId, $token)
    {
        $connect = new self();

        $connect->user       = $user;
        $connect->resource   = (string)$resource;
        $connect->resourceId = (int)$resourceId;
        $connect->token      = (string)$token;

        return $connect;
    }

    /**
     * @param string $data
     *
     * @return UserConnect
     */
    public static function createFromSerializedString($data, User $user)
    {
        $userConnect = new self();
        $userConnect->unserialize((string)$data);
        $userConnect->user = $user;

        return $userConnect;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function resource()
    {
        return $this->resource;
    }

    /**
     * @return string
     */
    public function resourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return string
     */
    public function token()
    {
        return $this->token;
    }

    private function __construct()
    {
    }

    /**
     * String representation of object
     *
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return json_encode([
            'id'          => $this->id,
            'resource'    => $this->resource,
            'resource_id' => $this->resourceId,
            'token'       => $this->token,
        ]);
    }

    /**
     * Constructs the object
     *
     * @link  http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $data = json_decode($serialized, true);

        $this->id         = $data['id'];
        $this->resource   = $data['resource'];
        $this->resourceId = $data['resource_id'];
        $this->token      = $data['token'];
    }
}

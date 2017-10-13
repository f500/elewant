<?php

declare(strict_types=1);

namespace Elewant\DevelopmentBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

final class DevelopmentUserResponse implements UserResponseInterface
{

    private $response;
    /**
     * @var ResourceOwnerInterface
     */
    private $resourceOwner;

    public function __construct(array $response, ResourceOwnerInterface $resourceOwner)
    {
        $this->response      = $response;
        $this->resourceOwner = $resourceOwner;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getResourceOwner()
    {
        return $this->resourceOwner;
    }

    public function getUsername()
    {
        return $this->response['identifier'];
    }

    public function getNickname()
    {
        return $this->response['nickname'];
    }

    public function getFirstName()
    {
        return $this->response['firstname'];
    }

    public function getLastName()
    {
        return $this->response['lastname'];
    }

    public function getRealName()
    {
        return $this->response['realname'];
    }

    public function getAccessToken()
    {
        return $this->response['accessToken'];
    }

    public function getRefreshToken()
    {
        return $this->response['refreshToken'];
    }

    public function getEmail()
    {
    }

    public function getProfilePicture()
    {
    }

    public function getTokenSecret()
    {
    }

    public function getExpiresIn()
    {
    }

    public function getOAuthToken()
    {
    }

    public function setResponse($response)
    {
    }

    public function setResourceOwner(ResourceOwnerInterface $resourceOwner)
    {
    }

    public function setOAuthToken(OAuthToken $token)
    {
    }
}

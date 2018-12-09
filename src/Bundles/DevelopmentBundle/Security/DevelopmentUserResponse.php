<?php

declare(strict_types=1);

namespace Bundles\DevelopmentBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

final class DevelopmentUserResponse implements UserResponseInterface
{
    /**
     * @var array
     */
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

    public function getResponse(): array
    {
        return $this->response;
    }

    public function getResourceOwner(): ResourceOwnerInterface
    {
        return $this->resourceOwner;
    }

    public function getUsername(): ?string
    {
        return $this->response['identifier'];
    }

    public function getNickname(): string
    {
        return $this->response['nickname'];
    }

    public function getFirstName(): ?string
    {
        return $this->response['firstname'];
    }

    public function getLastName(): ?string
    {
        return $this->response['lastname'];
    }

    public function getRealName(): ?string
    {
        return $this->response['realname'];
    }

    public function getAccessToken(): string
    {
        return $this->response['accessToken'];
    }

    public function getRefreshToken(): ?string
    {
        return $this->response['refreshToken'];
    }

    public function getEmail(): ?string
    {
        return null;
    }

    public function getProfilePicture(): ?string
    {
        return null;
    }

    public function getTokenSecret(): ?string
    {
        return null;
    }

    public function getExpiresIn(): ?string
    {
        return null;
    }

    public function getOAuthToken(): ?string
    {
        return null;
    }

    public function setResourceOwner(ResourceOwnerInterface $resourceOwner): void
    {
        return;
    }

    public function setOAuthToken(OAuthToken $token): void
    {
    }

    public function getData(): array
    {
        return $this->response;
    }

    public function setData($data): void
    {
        // @todo: Implement setData() method.
    }
}

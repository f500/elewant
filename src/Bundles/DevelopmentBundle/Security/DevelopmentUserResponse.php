<?php

declare(strict_types=1);

namespace Bundles\DevelopmentBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\AbstractUserResponse;

final class DevelopmentUserResponse extends AbstractUserResponse
{
    public function __construct(array $data, ResourceOwnerInterface $resourceOwner)
    {
        $this->setData($data);
        $this->setResourceOwner($resourceOwner);
    }

    public function getUsername(): string
    {
        return $this->data['identifier'] ?? '';
    }

    public function getNickname(): string
    {
        return $this->data['nickname'] ?? '';
    }

    public function getFirstName(): ?string
    {
        return $this->data['firstname'] ?? null;
    }

    public function getLastName(): ?string
    {
        return $this->data['lastname'] ?? null;
    }

    public function getRealName(): ?string
    {
        return $this->data['realname'] ?? null;
    }
}

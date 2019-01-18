<?php

declare(strict_types=1);

namespace Bundles\DevelopmentBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Exception\HttpTransportException;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\HttpFoundation\Request;

final class DevelopmentOauthResourceOwner implements ResourceOwnerInterface
{
    /** @var string */
    protected $name = 'twitter';

    public function getUserInformation(array $accessToken, array $extraParameters = []): UserResponseInterface
    {
        throw new HttpTransportException('Not implemented.', $this->name);
    }

    public function getAuthorizationUrl($redirectUri, array $extraParameters = []): string
    {
        return '';
    }

    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = []): array
    {
        return [];
    }

    public function isCsrfTokenValid($csrfToken): bool
    {
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOption($name)
    {
        return null;
    }

    public function handles(Request $request): bool
    {
        return true;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function addPaths(array $paths): void
    {
    }

    public function refreshAccessToken($refreshToken, array $extraParameters = []): void
    {
    }
}

<?php

declare(strict_types=1);

namespace Elewant\DevelopmentBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\Request;

final class DevelopmentOauthResourceOwner implements ResourceOwnerInterface
{

    public function getName()
    {
        return 'twitter';
    }

    public function isCsrfTokenValid($csrfToken)
    {
        return true;
    }

    public function handles(Request $request)
    {
        return true;
    }

    public function getUserInformation(array $accessToken, array $extraParameters = [])
    {
    }

    public function getAuthorizationUrl($redirectUri, array $extraParameters = [])
    {
    }

    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = [])
    {
    }

    public function getOption($name)
    {
    }

    public function setName($name)
    {
    }

    /**
     * Add extra paths to the configuration.
     *
     * @param array $paths
     */
    public function addPaths(array $paths)
    {
        // TODO: Implement addPaths() method.
    }

    /**
     * @param string $refreshToken    Refresh token
     * @param array  $extraParameters An array of parameters to add to the url
     */
    public function refreshAccessToken($refreshToken, array $extraParameters = [])
    {
        // TODO: Implement refreshAccessToken() method.
    }
}

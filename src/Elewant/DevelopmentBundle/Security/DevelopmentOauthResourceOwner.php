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
}

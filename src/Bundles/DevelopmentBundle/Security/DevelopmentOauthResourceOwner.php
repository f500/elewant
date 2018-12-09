<?php /** @noinspection PhpInconsistentReturnPointsInspection */

declare(strict_types=1);

namespace Bundles\DevelopmentBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwnerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\HttpFoundation\Request;

final class DevelopmentOauthResourceOwner implements ResourceOwnerInterface
{

    public function getName(): string
    {
        return 'twitter';
    }

    public function isCsrfTokenValid($csrfToken): bool
    {
        return true;
    }

    public function handles(Request $request): bool
    {
        return true;
    }

    public function getUserInformation(array $accessToken, array $extraParameters = []): UserResponseInterface
    {
        // @todo: Implement getUserInformation() method.
    }

    public function getAuthorizationUrl($redirectUri, array $extraParameters = []): string
    {
        // @todo: Implement getAuthorizationUrl() method.
    }

    public function getAccessToken(Request $request, $redirectUri, array $extraParameters = []): array
    {
        // @todo: Implement getAccessToken() method.
    }

    public function getOption($name)
    {
        // @todo: Implement getOption() method.
    }

    public function setName($name): void
    {
        // @todo: Implement setName() method.
    }

    /**
     * Add extra paths to the configuration.
     *
     * @param array $paths
     */
    public function addPaths(array $paths): void
    {
        // @todo: Implement addPaths() method.
    }

    /**
     * @param string $refreshToken    Refresh token
     * @param array  $extraParameters An array of parameters to add to the url
     */
    public function refreshAccessToken($refreshToken, array $extraParameters = []): void
    {
        // @todo: Implement refreshAccessToken() method.
    }
}

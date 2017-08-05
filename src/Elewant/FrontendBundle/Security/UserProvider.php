<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Security;

use Elewant\FrontendBundle\Entity\User;
use Elewant\FrontendBundle\Repository\UserRepository;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface, AccountConnectorInterface
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $username
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username) : UserInterface
    {
        $user = $this->repository->findUserByUsername($username);

        if ($user === null) {
            throw new UsernameNotFoundException(
                sprintf('User with username "%s" not found.', $username)
            );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user) : UserInterface
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class) : bool
    {
        return $class === User::class;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response) : UserInterface
    {
        $resource   = $response->getResourceOwner()->getName();
        $resourceId = (string) $response->getResponse()['id'] ?? 'UNKNOWN';

        $user = $this->repository->findUserByResource($resource, $resourceId);

        if ($user === null) {
            throw new AccountNotLinkedException(
                sprintf('User with resource "%s" and id "%s" not found.', $resource, $resourceId)
            );
        }

        return $user;
    }

    public function connect(UserInterface $user, UserResponseInterface $response) : void
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        /** @var User $user */
        if ($user->id() === null) {
            $user = User::register($response->getNickname(), $user->displayName(), $user->country());
        } else {
            $user = $this->refreshUser($user);
        }

        switch ($response->getResourceOwner()->getName()) {
            case 'twitter':
                $this->connectTwitter($user, $response);
                break;
            case 'facebook':
                $this->connectFacebook($user, $response);
                break;
            default:
                throw new \LogicException(
                    sprintf(
                        'Cannot connect user "%s" to resource "%s" with id "%s".',
                        $user->username(),
                        $response->getResourceOwner()->getName(),
                        $response->getResponse()['id'] ?? 'UNKNOWN'
                    )
                );
        }

        $this->repository->saveUser($user);
    }

    private function connectTwitter(User $user, UserResponseInterface $response) : void
    {
        $data = $response->getResponse();

        if (!isset($data['id'])) {
            throw new UnsupportedUserException(sprintf('Missing "id" in resource "twitter".'));
        }

        $user->connect(
            'twitter',
            (string) $data['id'],
            (string) $response->getAccessToken(),
            (string) $response->getRefreshToken()
        );
    }

    private function connectFacebook(User $user, UserResponseInterface $response) : void
    {
        $data = $response->getResponse();

        if (!isset($data['id'])) {
            throw new UnsupportedUserException(sprintf('Missing "id" in resource "facebook".'));
        }

        $user->connect(
            'facebook',
            (string) $data['id'],
            (string) $response->getAccessToken(),
            (string) $response->getRefreshToken()
        );
    }
}

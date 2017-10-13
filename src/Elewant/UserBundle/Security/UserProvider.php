<?php

declare(strict_types=1);

namespace Elewant\UserBundle\Security;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Elewant\UserBundle\Entity\User;
use Elewant\UserBundle\Event\UserHasRegistered;
use Elewant\UserBundle\Repository\UserRepository;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface, AccountConnectorInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(ManagerRegistry $registry, EventDispatcherInterface $eventDispatcher)
    {
        $this->registry        = $registry;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $username
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->repository()->findUserByUsername($username);

        if ($user === null) {
            throw new UsernameNotFoundException(sprintf('User with username "%s" not found.', $username));
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
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
    public function supportsClass($class): bool
    {
        return $class === User::class;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface
    {
        $resource   = $response->getResourceOwner()->getName();
        $resourceId = (string) $response->getResponse()['id'] ?? 'UNKNOWN';

        $user = $this->repository()->findUserByResource($resource, $resourceId);

        if ($user === null) {
            throw new AccountNotLinkedException(
                sprintf('User with resource "%s" and id "%s" not found.', $resource, $resourceId)
            );
        }

        return $user;
    }

    public function connect(UserInterface $user, UserResponseInterface $response): void
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        /** @var User $user */

        switch ($response->getResourceOwner()->getName()) {
            case 'twitter':
                $this->connectTwitter($user, $response);
                break;
            default:
                throw new AuthenticationException(
                    sprintf(
                        'Cannot connect user "%s" to resource "%s" with id "%s".',
                        $user->getUsername(),
                        $response->getResourceOwner()->getName(),
                        $response->getResponse()['id'] ?? 'UNKNOWN'
                    )
                );
        }

        $this->manager()->persist($user);
        $this->manager()->flush();

        $event = new UserHasRegistered($user);
        $this->eventDispatcher->dispatch(UserHasRegistered::NAME, $event);
    }

    private function connectTwitter(User $user, UserResponseInterface $response): void
    {
        $data = $response->getResponse();

        if (!isset($data['id'])) {
            throw new AuthenticationException(sprintf('Missing "id" in resource "twitter".'));
        }

        $user->connect(
            'twitter',
            (string) $data['id'],
            $response->getAccessToken(),
            (string) $response->getRefreshToken()
        );
    }

    private function manager(): ObjectManager
    {
        return $this->registry->getManager();
    }

    private function repository(): UserRepository
    {
        /** @var UserRepository $repository */
        $repository = $this->registry->getRepository(User::class);

        return $repository;
    }
}

<?php

namespace Elewant\FrontendBundle\Security;

use Elewant\FrontendBundle\Entity\User;
use Elewant\FrontendBundle\Repository\AuthenticationRepository;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface, AccountConnectorInterface
{
    /**
     * @var AuthenticationRepository
     */
    private $authenticationRepository;

    /**
     * @param AuthenticationRepository $authenticationRepository
     */
    public function __construct(AuthenticationRepository $authenticationRepository)
    {
        $this->authenticationRepository = $authenticationRepository;
    }


    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        // I have no idea what to do with this
    }

    /**
     * @inheritdoc
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $data = $response->getResponse();

        $resource   = $response->getResourceOwner()->getName();
        $resourceId = $data['id'];

        $user = $this->authenticationRepository->findUserByResource($resource, $resourceId);

        if (!$user) {
            throw new AccountNotLinkedException(sprintf("User '%s' not found.", $data['name']));
        }

        return new SecurityUser($user->id(), $user->username(), $user->displayname());
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === SecurityUser::class;
    }

    /**
     * Connects the response to the user object.
     *
     * @param UserInterface         $user     The user object
     * @param UserResponseInterface $response The oauth response
     *
     * @throws \Exception
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        if (!$response instanceof PathUserResponse) {
            throw new \Exception('What to do now?');
        }

        if (null === $user->id()) {
            $realUser = User::create($user->displayname(), $user->username());
        } else {
            $realUser = $this->authenticationRepository->findUserById($user->id());
        }

        $resourceName = $response->getResourceOwner()->getName();
        switch ($resourceName) {
            case 'twitter':
                $this->connectTwitter($realUser, $response);
                break;
            case 'facebook':
                $this->connectFacebook($realUser, $response);
                break;
            default:
                throw new \LogicException(
                    sprintf('Cannot find connector for resource "%s"', $resourceName)
                );
        }

        $this->authenticationRepository->saveUser($realUser);
    }

    /**
     * @param User             $user
     * @param PathUserResponse $response
     */
    private function connectTwitter(User $user, PathUserResponse $response)
    {
        $userData = $response->getResponse();

        $user->updateCountry($userData['location']);
        $user->connect('twitter', $userData['id'], $response->getAccessToken());
    }

    /**
     * @param User             $user
     * @param PathUserResponse $response
     */
    private function connectFacebook(User $user, PathUserResponse $response)
    {
        $userData = $response->getResponse();

        $user->connect('facebook', $userData['id'], $response->getAccessToken());
    }
}

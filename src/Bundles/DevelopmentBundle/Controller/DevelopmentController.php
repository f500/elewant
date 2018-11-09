<?php

declare(strict_types=1);

namespace Bundles\DevelopmentBundle\Controller;

use Bundles\DevelopmentBundle\Security\DevelopmentOauthResourceOwner;
use Bundles\DevelopmentBundle\Security\DevelopmentUserResponse;
use Bundles\UserBundle\Entity\User;
use Bundles\UserBundle\Repository\UserRepository;
use Bundles\UserBundle\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class DevelopmentController extends Controller
{
    /**
     * @Route("/list-users", name="dev_list_users")
     */
    public function listUsersAction(): Response
    {
        $users = $this->userRepository()->findAll();

        return $this->render(
            '@DevelopmentBundle/list_users.html.twig',
            ['users' => $users]
        );
    }

    /**
     * @Route("generate-new-user", name="dev_generate_new_user")
     *
     * @param UserProvider $userProvider
     *
     * @return RedirectResponse
     */
    public function generateNewUserAction(UserProvider $userProvider): Response
    {
        $user = $this->generateRandomNewUser();

        $userResponse = new DevelopmentUserResponse(
            [
                'id'           => $user->username(),
                'nickname'     => $user->displayName(),
                'accessToken'  => 'access-token',
                'refreshToken' => 'refresh-token',
            ],
            new DevelopmentOauthResourceOwner()
        );

        $userProvider->connect($user, $userResponse);

        return $this->redirectToRoute('dev_list_users');
    }

    /**
     * @Route("/login-as/{username}", name="dev_login_as")
     *
     * @param Request      $request
     * @param UserProvider $userProvider
     * @param string       $username
     *
     * @return RedirectResponse
     * @throws NonUniqueResultException
     */
    public function loginAsAction(Request $request, UserProvider $userProvider, string $username): Response
    {
        try {
            $user = $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            return $this->redirectToRoute('root');
        }

        $token = new UsernamePasswordToken($user, null, "secured_area", $user->getRoles());
        $this->get("security.token_storage")->setToken($token);

        //now dispatch the login event
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

        return $this->redirectToRoute('root');
    }

    private function userRepository(): UserRepository
    {
        /** @var EntityManager $em */
        $em = $this->get('doctrine.orm.entity_manager');

        /** @var UserRepository $userRepository */
        $userRepository = $em->getRepository(User::class);

        return $userRepository;
    }

    private function generateRandomNewUser(): User
    {
        $faker = Factory::create();

        return new User($faker->userName, $faker->name, 'NL');
    }
}

<?php

declare(strict_types=1);

namespace Elewant\DevelopmentBundle\Controller;

use Doctrine\ORM\EntityManager;
use Elewant\DevelopmentBundle\Security\DevelopmentOauthResourceOwner;
use Elewant\DevelopmentBundle\Security\DevelopmentUserResponse;
use Elewant\UserBundle\Entity\User;
use Elewant\UserBundle\Repository\UserRepository;
use Elewant\UserBundle\Security\UserProvider;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class DevelopmentController extends Controller
{
    /**
     * @Route("/list-users", name="dev_list_users")
     */
    public function listUsersAction()
    {
        $users = $this->userRepository()->findAll();

        return $this->render(
            'Development/list_users.html.twig',
            ['users' => $users]
        );
    }

    /**
     * @Route("generate-new-user", name="dev_generate_new_user")
     */
    public function generateNewUserAction(UserProvider $userProvider)
    {
        $user = $this->generateRandomNewNuser();

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
     */
    public function loginAsAction(Request $request, UserProvider $userProvider, $username)
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

    private function generateRandomNewNuser(): User
    {
        $faker = Factory::create();

        return new User($faker->userName, $faker->name, 'NL');
    }
}

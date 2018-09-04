<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Elewant\AppBundle\Entity\Herd;
use Elewant\AppBundle\Repository\HerdRepository;
use Elewant\UserBundle\Entity\User;
use Elewant\UserBundle\Security\UserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/shepherd", options={"expose"=true})
 */
class ShepherdController extends Controller
{

    /**
     * @Route("/admire/{username}", name="shepherd_admire_herd")
     */
    public function admireHerdAction(UserProvider $userProvider, HerdRepository $herdRepository, string $username)
    {
        $user = $this->getUserByUsername($username, $userProvider);
        $herd = $this->getHerd($user, $herdRepository);

        $data = [
            'user' => $user,
            'herd' => $herd,
        ];

        return $this->render('Shepherd/admire_herd.html.twig', $data);
    }

    /**
     * @Route("/search", name="shepherd_search")
     */
    public function searchAction(Request $request, HerdRepository $herdRepository)
    {
        $query = $request->get('q');
        $usernames = $herdRepository->search($query);

        return $this->json($usernames);
    }


    /**
     * @param UserProvider $userProvider
     * @param string $username
     *
     * @return User|UserInterface
     */
    private function getUserByUsername(string $username, UserProvider $userProvider): UserInterface
    {
        try {
            $user = $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            throw $this->createNotFoundException('error.shepherd.not-found');
        }

        return $user;
    }

    /**
     * @param User $user
     *
     * @param HerdRepository $herdRepository
     * @return Herd
     */
    private function getHerd(User $user, HerdRepository $herdRepository): Herd
    {
        $herd = $herdRepository->findOneByShepherdId($user->shepherdId());

        if ($herd === null) {
            throw $this->createNotFoundException('error.shepherd.herd-not-found');
        }

        return $herd;
    }
}

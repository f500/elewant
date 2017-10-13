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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    public function admireHerdAction($username)
    {
        $user = $this->getUserByUsername($username);
        $herd = $this->getHerd($user);

        $data = [
            'user' => $user,
            'herd' => $herd,
        ];

        return $this->render('ElewantAppBundle:Shepherd:admire_herd.html.twig', $data);
    }

    /**
     * @Route("/search", name="shepherd_search")
     */
    public function searchAction(Request $request)
    {
        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');

        $query     = $request->get('q');
        $usernames = $herdRepository->search($query);

        return $this->json($usernames);
    }


    /**
     * @param string $username
     *
     * @return User|UserInterface
     * @throws NotFoundHttpException
     */
    private function getUserByUsername(string $username): UserInterface
    {
        /** @var UserProvider $userProvider */
        $userProvider = $this->get('elewant.security.user_provider');

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
     * @return Herd
     * @throws NotFoundHttpException
     */
    private function getHerd(User $user): Herd
    {
        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');
        $herd           = $herdRepository->findOneByShepherdId($user->shepherdId());

        if ($herd === null) {
            throw $this->createNotFoundException('error.shepherd.herd-not-found');
        }

        return $herd;
    }
}

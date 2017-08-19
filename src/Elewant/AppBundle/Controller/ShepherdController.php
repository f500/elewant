<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Elewant\AppBundle\Entity\Herd;
use Elewant\AppBundle\Repository\HerdRepository;
use Elewant\UserBundle\Entity\User;
use Elewant\UserBundle\Security\UserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/shepherd")
 */
class ShepherdController extends Controller
{

    /**
     * @Route("/{username}", name="shepherd_admire_herd")
     */
    public function shepherdAdmireHerdAction($username)
    {
        try {
            $user = $this->getUserByName($username);
        } catch (UsernameNotFoundException $e) {
            throw $this->createNotFoundException('The user does not exist');
        }

        $herd = $this->getHerd($user);

        $data = [
            'herd' => $herd,
        ];

        return $this->render('ElewantAppBundle:Shepherd:admire-herd.html.twig', $data);
    }

    private function getUserByName(string $username) :? UserInterface
    {
        /** @var UserProvider $userProvider */
        $userProvider = $this->get('elewant.security.user_provider');
        $user         = $userProvider->loadUserByUsername($username);

        return $user;
    }

    /**
     * @param User|UserInterface $user
     *
     * @return Herd
     */
    private function getHerd(User $user) :? Herd
    {
        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');
        $herd           = $herdRepository->findOneByShepherdId($user->shepherdId());

        return $herd;
    }

}

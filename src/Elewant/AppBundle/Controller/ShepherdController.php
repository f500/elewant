<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Elewant\AppBundle\Entity\Herd;
use Elewant\AppBundle\Repository\HerdRepository;
use Elewant\UserBundle\Entity\User;
use Elewant\UserBundle\Security\UserProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $herd = $this->getHerdByUsername($username);

        $data = [
            'herd' => $herd,
        ];

        return $this->render('ElewantAppBundle:Shepherd:admire_herd.html.twig', $data);
    }

    /**
     * @param string $username
     *
     * @return User|UserInterface
     * @throws NotFoundHttpException
     */
    private function getUserByName(string $username) : UserInterface
    {
        /** @var UserProvider $userProvider */
        $userProvider = $this->get('elewant.security.user_provider');

        try {
            $user = $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            throw $this->createNotFoundException('There is no Shepherd with that name...');
        }

        return $user;
    }

    /**
     * @param string $username
     *
     * @return Herd
     * @throws NotFoundHttpException
     */
    private function getHerdByUsername(string $username) : Herd
    {
        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');
        $herd           = $herdRepository->findOneByName($username);

        if ($herd === null) {
            throw $this->createNotFoundException('This Shepherd does not seem to have a Herd...');
        }

        return $herd;
    }

}

<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

/**
 * Legacy "UserBundle" is okay to use!
 */

use Bundles\UserBundle\Entity\User;
use Bundles\UserBundle\Security\UserProvider;
use Doctrine\ORM\NonUniqueResultException;
use Elewant\Webapp\DomainModel\Herding\Herd;
use Elewant\Webapp\DomainModel\Herding\HerdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/shepherd", options={"expose"=true})
 */
final class ShepherdController extends AbstractController
{
    /**
     * @Route("/admire/{username}", name="shepherd_admire_herd")
     * @param UserProvider $userProvider
     * @param HerdRepository $herdRepository
     * @param string $username
     * @return Response
     */
    public function admireHerdAction(
        UserProvider $userProvider,
        HerdRepository $herdRepository,
        string $username
    ): Response
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
     * @param Request $request
     * @param HerdRepository $herdRepository
     * @return JsonResponse
     */
    public function searchAction(Request $request, HerdRepository $herdRepository): Response
    {
        $query = $request->get('q');
        $userNames = $herdRepository->search($query);

        return $this->json($userNames);
    }

    private function getUserByUsername(string $username, UserProvider $userProvider): UserInterface
    {
        try {
            $user = $userProvider->loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            throw $this->createNotFoundException('error.shepherd.not-found');
        } catch (NonUniqueResultException $e) {
            throw $this->createNotFoundException('error.herd.multiple-shepherds-found');
        }

        return $user;
    }

    private function getHerd(UserInterface $user, HerdRepository $herdRepository): Herd
    {
        if (!$user instanceof User) {
            throw $this->createNotFoundException('error.invalid-user-class');
        }

        try {
            $herd = $herdRepository->findOneByShepherdId($user->shepherdId());
        } catch (NonUniqueResultException $e) {
            throw $this->createNotFoundException('error.herd.multiple-herds-found');
        }

        if ($herd === null) {
            throw $this->createNotFoundException('error.herd.herd-not-found');
        }

        return $herd;
    }
}

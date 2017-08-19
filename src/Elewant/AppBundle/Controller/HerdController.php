<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Elewant\AppBundle\Entity\Herd;
use Elewant\AppBundle\Repository\HerdRepository;
use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Commands\AbandonElePHPant;
use Elewant\Herding\Model\Commands\AdoptElePHPant;
use Elewant\UserBundle\Entity\User;
use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/herd")
 * @Security("has_role('ROLE_USER')")
 */
class HerdController extends Controller
{

    /**
     * @Route("/tending", name="herd_tending")
     */
    public function herdTendingAction(UserInterface $user)
    {
        $herd = $this->getHerd($user);

        $data = [
            'herd'   => $herd,
            'breeds' => Breed::availableTypes(),
        ];

        return $this->render('ElewantAppBundle:Herd:tending.html.twig', $data);
    }

    /**
     * @Route("/adopt/{breed}", name="herd_adopt_breed")
     */
    public function adoptElePHPantAction(UserInterface $user, $breed)
    {
        $herd = $this->getHerd($user);

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        $command    = AdoptElePHPant::byHerd($herd->herdId(), $breed);

        $commandBus->dispatch($command);
        return new JsonResponse('adoption underway');
    }

    /**
     * @Route("/abandon/{breed}", name="herd_abandon_breed")
     */
    public function abandonElePHPantAction(UserInterface $user, $breed)
    {
        $herd = $this->getHerd($user);

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        $command    = AbandonElePHPant::byHerd($herd->herdId(), $breed);

        $commandBus->dispatch($command);

        return new JsonResponse('abandonment underway');
    }

    /**
     * @param User|UserInterface $user
     *
     * @return Herd
     */
    private function getHerd(User $user):? Herd
    {
        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');
        $herd = $herdRepository->findOneByShepherdId($user->shepherdId());

        return $herd;
    }

}

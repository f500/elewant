<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

/**
 * Legacy "UserBundle" is okay to use!
 * Herding commands are okay to use!
 *
 * @todo Is it ok to use Herding\DomainModel here?
 */

use Bundles\UserBundle\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Elewant\Herding\Application\Commands;
use Elewant\Herding\DomainModel\Breed\BreedCollection;
use Elewant\Webapp\DomainModel\Herding\Herd;
use Elewant\Webapp\DomainModel\Herding\HerdRepository;
use InvalidArgumentException;
use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/herd", options={"expose"=true})
 * @Security("has_role('ROLE_USER')")
 */
final class HerdController extends Controller
{
    /**
     * @Route("/tending", name="herd_tending")
     *
     * @param UserInterface  $user
     * @param HerdRepository $herdRepository
     *
     * @return Response
     */
    public function herdTendingAction(UserInterface $user, HerdRepository $herdRepository): Response
    {
        $herd = $this->getHerd($user, $herdRepository);

        $data = [
            'user'              => $user,
            'herd'              => $herd,
            'allUnwantedBreeds' => $herd->desiredBreeds()->isMissingBreedsWhenComparedTo(BreedCollection::all()),
            'regularBreeds'     => BreedCollection::allRegular(),
            'largeBreeds'       => BreedCollection::allLarge(),
        ];

        return $this->render('Herd/tending.html.twig', $data);
    }

    /**
     * @Route("/adopt/{breed}", name="herd_adopt_breed")
     *
     * @param UserInterface  $user
     * @param HerdRepository $herdRepository
     * @param string         $breed
     *
     * @return Response
     */
    public function adoptElePHPantAction(
        UserInterface $user,
        HerdRepository $herdRepository,
        string $breed
    ): Response {
        $herd = $this->getHerd($user, $herdRepository);

        $command = Commands\AdoptElePHPant::byHerd($herd->herdId(), $breed);
        $this->getCommandBus()->dispatch($command);

        return new JsonResponse('adopt_breed_underway');
    }

    /**
     * @Route("/abandon/{breed}", name="herd_abandon_breed")
     *
     * @param UserInterface  $user
     * @param HerdRepository $herdRepository
     * @param string         $breed
     *
     * @return Response
     */
    public function abandonElePHPantAction(
        UserInterface $user,
        HerdRepository $herdRepository,
        string $breed
    ): Response {
        $herd = $this->getHerd($user, $herdRepository);

        $command = Commands\AbandonElePHPant::byHerd($herd->herdId(), $breed);
        $this->getCommandBus()->dispatch($command);

        return new JsonResponse('abandon_breed_underway');
    }

    /**
     * @Route("/desire/{breed}", name="herd_desire_breed")
     *
     * @param UserInterface  $user
     * @param HerdRepository $herdRepository
     * @param string         $breed
     *
     * @return Response
     */
    public function desireBreedAction(
        UserInterface $user,
        HerdRepository $herdRepository,
        string $breed
    ): Response {
        $herd = $this->getHerd($user, $herdRepository);

        $command = Commands\DesireBreed::byHerd($herd->herdId(), $breed);
        $this->getCommandBus()->dispatch($command);

        return new JsonResponse('desire_breed_underway');
    }

    /**
     * @Route("/eliminate-desire-for/{breed}", name="herd_eliminate_desire_for_breed")
     *
     * @param UserInterface  $user
     * @param HerdRepository $herdRepository
     * @param string         $breed
     *
     * @return Response
     */
    public function eliminateDesireForBreedAction(
        UserInterface $user,
        HerdRepository $herdRepository,
        string $breed
    ): Response {

        $herd = $this->getHerd($user, $herdRepository);

        $command = Commands\EliminateDesireForBreed::byHerd($herd->herdId(), $breed);
        $this->getCommandBus()->dispatch($command);

        return new JsonResponse('desire_breed_underway');
    }

    /**
     * @param UserInterface  $user
     * @param HerdRepository $herdRepository
     *
     * @return Herd
     */
    private function getHerd(UserInterface $user, HerdRepository $herdRepository): Herd
    {
        if (!$user instanceof User) {
            throw new InvalidArgumentException('Wrong kind of user');
        }

        try {
            $herd = $herdRepository->findOneByShepherdId($user->shepherdId());
        } catch (NonUniqueResultException $exception) {
            // @todo: Should this be a 404?
            throw $this->createNotFoundException('error.herd.multiple-herds-found');
        }

        if ($herd === null) {
            throw $this->createNotFoundException('error.herd.herd-not-found');
        }

        return $herd;
    }

    private function getCommandBus(): CommandBus
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');

        return $commandBus;
    }
}

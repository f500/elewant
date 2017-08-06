<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Commands\AbandonElePHPant;
use Elewant\Herding\Model\Commands\AdoptElePHPant;
use Elewant\Herding\Model\Commands\FormHerd;
use Elewant\Herding\Projections\HerdListing;
use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/example")
 */
class HerdingExampleController extends Controller
{
    private $shepherdId = '00000000-0000-0000-0000-000000000000';
    private $herdNameSeed = ['honey','rob','delight','change','merciful','achiever','match','quick','spare',
                             'chubby','grain','frame','division','nose','reply','icicle','improve','wool','amusing',
                             'meek','substantial','bee','earn','word'];

    /**
     * @Route("/", name="herd_list")
     */
    public function testingListAction()
    {
        /** @var HerdListing $herdListing */
        $herdListing = $this->container->get('elewant.herd_projection.herd_listing');

        $herds = $herdListing->findAll();

        return $this->render('ElewantFrontendBundle:Default:herd_list.html.twig', ['herds' => $herds]);
    }

    /**
     * @Route("/form", name="herd_form")
     */
    public function testingFormAdoptAction()
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        shuffle($this->herdNameSeed);
        $herdName = ucfirst(implode(' ', array_slice($this->herdNameSeed, 0, 3)));

        $command = FormHerd::forShepherd($this->shepherdId, $herdName);
        $commandBus->dispatch($command);

        return $this->redirectToRoute('herd_list');
    }

    /**
     * @Route("/{herdId}", name="herd_show")
     */
    public function testingHerdAction($herdId)
    {
        /** @var HerdListing $herdListing */
        $herdListing = $this->container->get('elewant.herd_projection.herd_listing');

        $herd       = $herdListing->findById($herdId);
        $elephpants = $herdListing->findElephpantsByHerdId($herdId);

        $data = [
            'herd'       => $herd,
            'elephpants' => $elephpants,
        ];

        return $this->render('ElewantFrontendBundle:Default:herd_show.html.twig', $data);
    }

    /**
     * @Route("/{herdId}/adopt/{breed}", name="herd_adopt")
     */
    public function testingHerdAdoptAction($herdId, $breed)
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        $command    = AdoptElePHPant::byHerd($herdId, $breed);

        $commandBus->dispatch($command);

        return $this->redirectToRoute('herd_show', ['herdId' => $herdId]);
    }

    /**
     * @Route("/{herdId}/abandon/{breed}", name="herd_abandon")
     */
    public function testingHerdAbandonAction($herdId, $breed)
    {
        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        $command    = AbandonElePHPant::byHerd($herdId, $breed);

        $commandBus->dispatch($command);

        return $this->redirectToRoute('herd_show', ['herdId' => $herdId]);
    }


}

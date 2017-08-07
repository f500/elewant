<?php

declare(strict_types=1);

namespace Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Commands\AbandonElePHPant;
use Elewant\Herding\Model\Commands\AbandonHerd;
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
    public function listHerdsAction()
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var HerdListing $herdListing */
        $herdListing = $this->container->get('elewant.herd_projection.herd_listing');

        $herds = $herdListing->findAll();

        return $this->render('ElewantFrontendBundle:Example:herd_list.html.twig', ['herds' => $herds]);
    }

    /**
     * @Route("/form", name="herd_form")
     */
    public function formHerdAction()
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        shuffle($this->herdNameSeed);
        $herdName = ucfirst(implode(' ', array_slice($this->herdNameSeed, 0, 3)));

        $command = FormHerd::forShepherd($this->shepherdId, $herdName);
        $commandBus->dispatch($command);

        return $this->redirectToRoute('herd_list');
    }

    /**
     * @Route("/top5", name="herd_top_5")
     */
    public function top5Action()
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var HerdListing $herdListing */
        $herdListing = $this->container->get('elewant.herd_projection.herd_listing');

        $herds = $herdListing->lastNewHerds(5);
        $elePHPants = $herdListing->lastNewElePHPants(5);

        return $this->render('ElewantFrontendBundle:Example:top5.html.twig', ['herds' => $herds, 'elephpants' => $elePHPants]);
    }

    /**
     * @Route("/{herdId}", name="herd_show")
     */
    public function showHerdAction($herdId)
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var HerdListing $herdListing */
        $herdListing = $this->container->get('elewant.herd_projection.herd_listing');

        $herd       = $herdListing->findById($herdId);
        $elephpants = $herdListing->findElephpantsByHerdId($herdId);

        $data = [
            'herd'       => $herd,
            'elephpants' => $elephpants,
        ];

        return $this->render('ElewantFrontendBundle:Example:herd_show.html.twig', $data);
    }

    /**
     * @Route("/{herdId}/adopt/{breed}", name="herd_adopt_elephpant")
     */
    public function adoptElePHPantAction($herdId, $breed)
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        $command    = AdoptElePHPant::byHerd($herdId, $breed);

        $commandBus->dispatch($command);

        return $this->redirectToRoute('herd_show', ['herdId' => $herdId]);
    }

    /**
     * @Route("/{herdId}/abandon/{breed}", name="herd_abandon_elephpant")
     */
    public function abandonElePHPantAction($herdId, $breed)
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        $command    = AbandonElePHPant::byHerd($herdId, $breed);

        $commandBus->dispatch($command);

        return $this->redirectToRoute('herd_show', ['herdId' => $herdId]);
    }

    /**
     * @Route("/{herdId}/abandon", name="herd_abandon")
     */
    public function abandonHerdAction($herdId)
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var CommandBus $commandBus */
        $commandBus = $this->get('prooph_service_bus.herding_command_bus');
        $command    = AbandonHerd::forShepherd($herdId, $this->shepherdId);

        $commandBus->dispatch($command);

        return $this->redirectToRoute('herd_list');
    }

    private function accessNotAllowed() {
        return !$this->get('kernel')->isDebug();
    }
}

<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Elewant\AppBundle\Repository\HerdRepository;
use Elewant\Herding\Model\Breed;
use Elewant\Herding\Model\Commands\AbandonElePHPant;
use Elewant\Herding\Model\Commands\AbandonHerd;
use Elewant\Herding\Model\Commands\AdoptElePHPant;
use Elewant\Herding\Model\Commands\FormHerd;
use Prooph\ServiceBus\CommandBus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/example")
 */
class HerdingExampleController extends Controller
{
    private $shepherdId = '00000000-0000-0000-0000-000000000000';
    private $herdNameSeed = [
        'honey',
        'rob',
        'delight',
        'change',
        'merciful',
        'achiever',
        'match',
        'quick',
        'spare',
        'chubby',
        'grain',
        'frame',
        'division',
        'nose',
        'reply',
        'icicle',
        'improve',
        'wool',
        'amusing',
        'meek',
        'substantial',
        'bee',
        'earn',
        'word',
    ];

    /**
     * @Route("/", name="herd_list")
     */
    public function listHerdsAction()
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');

        $herds = $herdRepository->findAll();

        return $this->render('ElewantAppBundle:Example:herd_list.html.twig', ['herds' => $herds]);
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

        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');

        $data = [
            'newest_herds'      => $herdRepository->lastNewHerds(5),
            'newest_elephpants' => $herdRepository->lastNewElePHPants(5),
        ];

        return $this->render('ElewantAppBundle:Example:top5.html.twig', $data);
    }

    /**
     * @Route("/search", name="herd_search")
     */
    public function searchAction(Request $request)
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        $data = [
            'search'  => $request->query->get('q'),
            'matches' => [],
        ];

        if ($request->query->get('q')) {
            /** @var HerdRepository $herdRepository */
            $herdRepository = $this->get('elewant.herd.herd_repository');

            $data['matches'] = $herdRepository->search($request->query->get('q'));
        }

        return $this->render('ElewantAppBundle:Example:search.html.twig', $data);
    }

    /**
     * @Route("/{herdId}", name="herd_show")
     */
    public function showHerdAction($herdId)
    {
        if ($this->accessNotAllowed()) {
            return $this->redirectToRoute('root');
        }

        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');

        $herd = $herdRepository->find($herdId);

        $data = [
            'herd'   => $herd,
            'breeds' => Breed::availableTypes(),
        ];

        if (count($herd->elephpants()) === 0) {
            return $this->render('ElewantAppBundle:Example:herd_form.html.twig', $data);
        } else {
            return $this->render('ElewantAppBundle:Example:herd_show.html.twig', $data);
        }
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

    private function accessNotAllowed()
    {
        return !$this->get('kernel')->isDebug();
    }
}

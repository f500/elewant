<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Elewant\AppBundle\Repository\HerdRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function indexAction()
    {
        /** @var HerdRepository $herdRepository */
        $herdRepository = $this->get('elewant.herd.herd_repository');
        $newestHerds    = $herdRepository->lastNewHerds(4);

        return $this->render(
            'ElewantAppBundle:Default:index.html.twig',
            [
                'shrinking_navbar' => true,
                'only_anchors_in_navbar' => true,
                'newest_herds'      => $newestHerds,
            ]
        );
    }

    /**
     * @Route("/history", name="history")
     */
    public function historyAction()
    {
        return $this->render(
            'ElewantAppBundle:Default:history.html.twig',
            []
        );
    }

    /**
     * @Route("/style-guide", name="style_guide")
     */
    public function styleGuideAction()
    {
        return $this->render(
            'ElewantAppBundle:Default:style_guide.html.twig'
        );
    }
}

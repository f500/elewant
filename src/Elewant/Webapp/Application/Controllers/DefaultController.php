<?php

declare(strict_types=1);

namespace Elewant\Webapp\Application\Controllers;

use Elewant\Webapp\DomainModel\Herding\HerdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="root")
     * @param HerdRepository $herdRepository
     * @return Response
     */
    public function indexAction(HerdRepository $herdRepository): Response
    {
        $newestHerds = $herdRepository->newestHerds(4);

        return $this->render(
            'Default/index.html.twig',
            [
                'shrinking_navbar' => true,
                'only_anchors_in_navbar' => true,
                'newest_herds' => $newestHerds,
            ]
        );
    }

    /**
     * @Route("/history", name="history")
     * @return Response
     */
    public function historyAction(): Response
    {
        return $this->render(
            'Default/history.html.twig',
            []
        );
    }

    /**
     * @Route("/style-guide", name="style_guide")
     * @return Response
     */
    public function styleGuideAction(): Response
    {
        return $this->render(
            'Default/style_guide.html.twig'
        );
    }
}

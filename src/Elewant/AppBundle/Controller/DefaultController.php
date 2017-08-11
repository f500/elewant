<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function indexAction()
    {
        return $this->render(
            'ElewantAppBundle:Default:index.html.twig',
            ['shrinking_navbar' => true]
        );
    }
}

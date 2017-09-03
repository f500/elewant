<?php

namespace Elewant\AppBundle\Twig;

use Elewant\AppBundle\Service\ContributorList;
use Twig_SimpleFunction;

class ContributorsExtension extends \Twig_Extension
{
    protected $githubService;

    public function __construct(ContributorList $githubService)
    {
        $this->githubService = $githubService;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('contributors', [$this, 'contributors']),
        ];
    }

    public function contributors(): array
    {
        return $this->githubService->allContributors();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elewant_contributors_extension';
    }
}

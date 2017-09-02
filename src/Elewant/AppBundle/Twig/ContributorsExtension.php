<?php

namespace Elewant\AppBundle\Twig;

use Elewant\AppBundle\Service\GithubService;
use Twig_SimpleFunction;

class ContributorsExtension extends \Twig_Extension
{
    protected $githubService;

    public function __construct(GithubService $githubService)
    {
        $this->githubService = $githubService;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('getContributors', [$this, 'getContributors']),
        ];
    }

    public function getContributors(): array
    {
        return $this->githubService->getAllContributors();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elewant_contributors_extension';
    }
}

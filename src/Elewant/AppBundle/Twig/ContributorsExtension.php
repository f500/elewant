<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Twig;

use Elewant\AppBundle\Service\ContributorList;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ContributorsExtension extends AbstractExtension
{
    protected $githubService;

    public function __construct(ContributorList $githubService)
    {
        $this->githubService = $githubService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('contributors', [$this, 'contributors']),
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

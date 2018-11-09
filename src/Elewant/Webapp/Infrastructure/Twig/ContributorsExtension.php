<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Twig;

use Elewant\Webapp\DomainModel\Contributor\ContributorList;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ContributorsExtension extends AbstractExtension
{
    protected $githubService;

    public function __construct(ContributorList $githubService)
    {
        $this->githubService = $githubService;
    }

    public function getFunctions(): array
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
    public function getName(): string
    {
        return 'elewant_contributors_extension';
    }
}

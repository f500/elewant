<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Twig;

use Elewant\Webapp\DomainModel\Contributor\Contributor;
use Elewant\Webapp\DomainModel\Contributor\ContributorList;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ContributorsExtension extends AbstractExtension
{
    protected ContributorList $githubService;

    public function __construct(ContributorList $githubService)
    {
        $this->githubService = $githubService;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('contributors', [$this, 'contributors']),
        ];
    }

    /**
     * @return Contributor[]
     */
    public function contributors(): array
    {
        return $this->githubService->allContributors();
    }

    public function getName(): string
    {
        return 'elewant_contributors_extension';
    }
}

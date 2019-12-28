<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Github;

use Elewant\Webapp\DomainModel\Contributor\Contributor;

final class GithubContributor implements Contributor
{
    private string $avatarUrl;

    private string $name;

    private int $contributionCount;

    private function __construct(string $avatarUrl, string $name, int $contributionCount)
    {
        $this->avatarUrl = $avatarUrl;
        $this->name = $name;
        $this->contributionCount = $contributionCount;
    }

    /**
     * @param mixed[] $data
     * @return Contributor
     */
    public static function fromGithubApiCall(array $data): Contributor
    {
        return new self(
            (string) $data['avatar_url'],
            (string) $data['login'],
            (int) $data['contributions']
        );
    }

    public function avatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function contributionCount(): int
    {
        return $this->contributionCount;
    }
}

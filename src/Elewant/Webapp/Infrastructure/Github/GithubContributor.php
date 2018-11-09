<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Github;

use Elewant\Webapp\DomainModel\Contributor\Contributor;

final class GithubContributor implements Contributor
{
    /**
     * @var string
     */
    private $avatarUrl;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $contributionCount;

    private function __construct(string $avatarUrl, string $name, int $contributionCount)
    {
        $this->avatarUrl         = $avatarUrl;
        $this->name              = $name;
        $this->contributionCount = $contributionCount;
    }

    public static function fromGithubApiCall(array $data): Contributor
    {
        return new self($data['avatar_url'], $data['login'], $data['contributions']);
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

<?php

declare(strict_types=1);

namespace Elewant\Webapp\DomainModel\Contributor;

interface Contributor
{
    public function avatarUrl(): string;

    public function name(): string;

    public function contributionCount(): int;
}

<?php

namespace Elewant\AppBundle\Service;

interface Contributor
{
    public function avatarUrl(): string;
    public function name(): string;
    public function contributionCount(): int;
}

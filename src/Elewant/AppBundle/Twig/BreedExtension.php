<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class BreedExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('breedColor', [$this, 'breedColorFilter']),
            new TwigFilter('breedSize', [$this, 'breedSizeFilter']),
            new TwigFilter('breedName', [$this, 'breedNameFilter']),
        ];
    }

    public function breedColorFilter(string $breed): string
    {
        return strtolower(explode("_", $breed)[0]);
    }

    public function breedSizeFilter(string $breed): string
    {
        $parts = explode("_", $breed);

        return strtolower(end($parts));
    }

    public function breedNameFilter(string $breed): string
    {
        $parts = explode("_", $breed);
        $parts = array_slice($parts, 1, -1);

        return strtolower(implode('_', $parts));
    }
}

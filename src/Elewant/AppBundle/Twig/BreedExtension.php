<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;

final class BreedExtension extends Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('breedColor', [$this, 'breedColorFilter']),
            new Twig_SimpleFilter('breedSize', [$this, 'breedSizeFilter']),
            new Twig_SimpleFilter('breedName', [$this, 'breedNameFilter']),
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
        return strtolower(implode('_',$parts));
    }

}

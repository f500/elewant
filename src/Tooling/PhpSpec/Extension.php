<?php

declare(strict_types=1);

namespace Tooling\PhpSpec;

use PhpSpec\Extension as PhpSpecExtension;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\Matcher;
use PhpSpec\ServiceContainer;

final class Extension implements PhpSpecExtension
{
    /**
     * @param ServiceContainer $container
     * @param mixed[] $params
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    public function load(ServiceContainer $container, array $params): void
    {
        $container->define(
            'elewant.matchers.be_equal',
            static function (ServiceContainer $c): Matcher {
                /** @var Presenter $presenter */
                $presenter = $c->get('formatter.presenter');

                return new EqualMatcher($presenter);
            },
            ['matchers']
        );
    }
}

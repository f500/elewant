<?php

declare(strict_types=1);

namespace Elewant\Tooling\PhpSpec;

use Elewant\Tooling\PhpSpec\Matchers\Equal;
use PhpSpec\Extension as PhpSpecExtension;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\ServiceContainer;

final class Extension implements PhpSpecExtension
{
    public function load(ServiceContainer $container, array $params): void
    {
        $container->define(
            'elewant.matchers.be_equal',
            function (ServiceContainer $c) {
                /** @var Presenter $presenter */
                $presenter = $c->get('formatter.presenter');

                return new Equal($presenter);
            },
            ['matchers']
        );
    }
}

<?php

namespace Elewant\Tooling\PhpSpec;

use Elewant\Tooling\PhpSpec\Matchers\Equal;
use PhpSpec\ServiceContainer;

final class Extension implements \PhpSpec\Extension
{
    /**
     * @param ServiceContainer $container
     * @param array $params
     */
    public function load(ServiceContainer $container, array $params)
    {
        $container->define(
            'elewant.matchers.be_equal',
            function ($c) {
                return new Equal($c->get('formatter.presenter'));
            },
            ['matchers']
        );
    }
}

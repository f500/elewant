<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$kernel = new Kernel('test', true);
$kernel->boot();

$app = new Application($kernel);

$run = static function (Application $application, string $command, array $parameters = []): void {
    $parameters['command'] = $command;
    $parameters['--no-interaction'] = true;
    $parameters['--quiet'] = true;

    $input = new ArrayInput($parameters);
    $input->setInteractive(false);

    $output = new ConsoleOutput(ConsoleOutput::VERBOSITY_QUIET);

    $application->doRun($input, $output);
};

$run($app, 'doctrine:database:drop', ['--force' => true]);

$run($app, 'doctrine:database:create');

$run($app, 'doctrine:migrations:migrate');

$run($app, 'event-store:event-stream:create');

<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$kernel = new Kernel('test', true); // create a "test" kernel
$kernel->boot();

$application = new Application($kernel);

$application->doRun(
    new ArrayInput(
        [
            'command' => 'doctrine:database:drop',
            '--force' => true,
        ]
    ),
    new ConsoleOutput(ConsoleOutput::VERBOSITY_QUIET)
);

$application->doRun(
    new ArrayInput(
        [
            'command' => 'doctrine:database:create',
        ]
    ),
    new ConsoleOutput(ConsoleOutput::VERBOSITY_QUIET)
);

$input = new ArrayInput([
        'command'          => 'doctrine:migrations:migrate',
        '--quiet'          => true,
        '--no-interaction' => true,
]);
$input->setInteractive(false);
$application->doRun(
    $input,
    new ConsoleOutput(ConsoleOutput::VERBOSITY_QUIET)
);

$input = new ArrayInput(['command' => 'event-store:event-stream:create']);
$input->setInteractive(false);
$application->doRun(
    $input,
    new ConsoleOutput(ConsoleOutput::VERBOSITY_QUIET)
);

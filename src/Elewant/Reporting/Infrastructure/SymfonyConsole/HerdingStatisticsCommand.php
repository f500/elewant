<?php

declare(strict_types=1);

namespace Elewant\Reporting\Infrastructure\SymfonyConsole;

use DateTimeImmutable;
use Elewant\Reporting\DomainModel\HerdingStatisticsCalculator;
use Elewant\Reporting\DomainModel\HerdingStatisticsGenerated;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class HerdingStatisticsCommand extends Command
{
    private HerdingStatisticsCalculator $herdingStatistics;

    private EventDispatcherInterface $dispatcher;

    public function __construct(HerdingStatisticsCalculator $herdingStatistics, EventDispatcherInterface $dispatcher)
    {
        $this->herdingStatistics = $herdingStatistics;
        $this->dispatcher = $dispatcher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('herding:statistics');
        $this->addArgument(
            'from',
            InputArgument::OPTIONAL,
            'The starting date for the statistics to generate (Y-m-d)'
        );
        $this->addArgument(
            'to',
            InputArgument::OPTIONAL,
            'The end date for the statistics to generate (Y-m-d)'
        );

        $this->addOption(
            'notify',
            't',
            InputOption::VALUE_NONE,
            'If this option is set, the HerdingStatisticsGenerated event is dispatched.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        [$from, $to] = $this->prepareDateArguments($input->getArguments()['from'], $input->getArguments()['to']);

        $statistics = $this->herdingStatistics->generate($from, $to);

        if ($input->getOption('notify')) {
            $this->dispatcher->dispatch(new HerdingStatisticsGenerated($statistics));
        }

        $output->writeln('From ' . $statistics->from()->format('Y-m-d') . ' to ' . $statistics->to()->format('Y-m-d'));
        $output->writeln('Number of new Herds: ' . $statistics->numberOfNewHerds());
        $output->writeln('Number of new ElePHPants: ' . $statistics->numberOfNewElePHPants());

        return 1;
    }

    /**
     * @param string|null $inputFrom
     * @param string|null $inputTo
     * @return DateTimeImmutable[]
     * @throws Exception
     */
    private function prepareDateArguments(?string $inputFrom, ?string $inputTo): array
    {
        $from = new DateTimeImmutable($inputFrom ?? 'monday last week');
        $to = $inputTo === null
            ? $from->modify('+6 days')
            : new DateTimeImmutable($inputTo);

        if ($to < $from) {
            $to = $from->modify('+6 days');
        }

        return [
            $from,
            $to,
        ];
    }
}

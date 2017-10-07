<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Command;

use DateTimeImmutable;
use DateTimeInterface;
use Elewant\AppBundle\Event\HerdingStatisticsGenerated;
use Elewant\AppBundle\Service\HerdingStatisticsCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HerdingStatisticsCommand extends Command
{
    /**
     * @var HerdingStatisticsCalculator
     */
    private $herdingStatistics;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(HerdingStatisticsCalculator $herdingStatistics, EventDispatcherInterface $dispatcher)
    {
        $this->herdingStatistics = $herdingStatistics;
        $this->dispatcher        = $dispatcher;

        parent::__construct();
    }

    protected function configure()
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        [$from, $to] = $this->prepareDateArguments($input->getArgument('from'), $input->getArgument('to'));

        $statistics = $this->herdingStatistics->generate($from, $to);

        if ($input->getOption('notify')) {
            $this->dispatcher->dispatch(HerdingStatisticsGenerated::NAME, new HerdingStatisticsGenerated($statistics));
        }

        $output->writeln('From ' . $statistics->from()->format('Y-m-d') . ' to ' . $statistics->to()->format('Y-m-d'));
        $output->writeln('Number of new Herds: ' . $statistics->numberOfNewHerds());
        $output->writeln('Number of new ElePHPants: ' . $statistics->numberOfNewElePHPants());
    }

    /**
     * @param $inputFrom
     * @param $inputTo
     *
     * @return DateTimeInterface[]
     */
    private function prepareDateArguments($inputFrom, $inputTo): array
    {
        $from = new DateTimeImmutable($inputFrom ?? 'monday last week');
        $to   = ($inputTo === null) ? $from->modify('+1 6 days') : new DateTimeImmutable($inputTo);

        if ($to < $from) {
            $to = $from->modify('+1 6 days');
        }

        return [
            $from,
            $to,
        ];
    }
}

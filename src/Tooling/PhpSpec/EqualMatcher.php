<?php

declare(strict_types=1);

namespace Tooling\PhpSpec;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Matcher\BasicMatcher;

final class EqualMatcher extends BasicMatcher
{

    /**
     * @var Presenter
     */
    private $presenter;

    /**
     * @param Presenter $presenter
     */
    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Make sure to return a higher number than the default "identity" matcher of PhpSpec (100)
     * @return int
     */
    public function getPriority(): int
    {
        return 101;
    }


    /**
     * {@inheritdoc}
     */
    public function supports(string $name, $subject, array $arguments): bool
    {
        return 'equal' === $name
            && is_object($subject)
            && method_exists($subject, 'equals')
            && 1 === count($arguments);
    }

    /**
     * {@inheritdoc}
     */
    protected function matches($subject, array $arguments): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $subject->equals($arguments[0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        return new FailureException(
            sprintf(
                'Expected %s to equal %s, but it does not.',
                $this->presenter->presentValue($subject),
                $this->presenter->presentValue($arguments[0])
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        return new FailureException(
            sprintf(
                'Expected %s not to equal %s, but it does.',
                $this->presenter->presentValue($subject),
                $this->presenter->presentValue($arguments[0])
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace Tooling\SymfonyAsset;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

final class FileVersionStrategy implements VersionStrategyInterface
{
    /**
     * @var string
     */
    private $version;

    public function __construct(string $versionFile)
    {
        if (!is_file($versionFile)) {
            throw new InvalidArgumentException(sprintf('Version-file "%s" does not exist', $versionFile));
        }

        $content = file_get_contents($versionFile);

        if ($content === false) {
            throw new RuntimeException(sprintf('Version-file "%s" could not be read', $versionFile));
        }

        $content = trim(strtolower($content), "v \t\n\r\0\x0B");

        if (!$content) {
            throw new RuntimeException(sprintf('Version-file "%s" is empty', $versionFile));
        }

        $this->version = $content;
    }

    /**
     * @inheritdoc
     */
    public function getVersion($path): string
    {
        return $this->version;
    }

    /**
     * @inheritdoc
     */
    public function applyVersion($path): string
    {
        $versionedPath = sprintf('%s?v=%s', ltrim($path, '/'), $this->getVersion($path));

        if ($path && '/' === $path[0]) {
            return '/' . $versionedPath;
        }

        return $versionedPath;
    }
}

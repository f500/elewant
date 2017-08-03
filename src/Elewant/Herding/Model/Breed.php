<?php

namespace Elewant\Herding\Model;

class Breed
{
    const GREEN = 'green';
    const BLUE = 'blue';
    const RED = 'red';

    private static $validTypes = [
        self::GREEN,
        self::BLUE,
        self::RED,
    ];

    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromString(string $type)
    {
        if (!in_array($type, self::$validTypes)) {
            throw SorryThatIsAnInvalid::breed($type);
        }

        return new self($type);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function equals($other): bool
    {
        return ($other instanceof static && $other->type === $this->type);
    }

    public function toString(): string
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

}

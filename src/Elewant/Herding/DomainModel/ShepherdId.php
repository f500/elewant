<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ShepherdId
{
    private UuidInterface $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): self
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return new self(Uuid::uuid4());
    }

    /**
     * @param string $shepherdId
     * @return ShepherdId
     * @throws SorryThatIsAnInvalid
     */
    public static function fromString(string $shepherdId): self
    {
        try {
            return new self(Uuid::fromString($shepherdId));
        } catch (InvalidUuidStringException $exception) {
            throw SorryThatIsAnInvalid::shepherdId($shepherdId);
        }
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @param mixed $other
     * @return bool
     */
    public function equals($other): bool
    {
        return $other instanceof static && $other->uuid->equals($this->uuid);
    }
}

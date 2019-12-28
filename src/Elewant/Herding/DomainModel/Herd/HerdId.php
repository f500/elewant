<?php

declare(strict_types=1);

namespace Elewant\Herding\DomainModel\Herd;

use Elewant\Herding\DomainModel\SorryThatIsAnInvalid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class HerdId
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
     * @param string $herdId
     * @return HerdId
     * @throws SorryThatIsAnInvalid
     */
    public static function fromString(string $herdId): self
    {
        try {
            return new self(Uuid::fromString($herdId));
        } catch (InvalidUuidStringException $exception) {
            throw SorryThatIsAnInvalid::herdId($herdId);
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

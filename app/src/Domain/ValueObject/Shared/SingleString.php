<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Shared;

use App\Domain\Exception\InvalidArgumentException;

abstract class SingleString
{
    protected const MIN_LENGTH = 2;
    protected const MAX_LENGTH = 255;

    public function __construct(private string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Name cannot be empty');
        }

        if (mb_strlen($value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                \sprintf('Name cannot be longer than %d characters', self::MAX_LENGTH)
            );
        }

        if (mb_strlen($value) < self::MIN_LENGTH) {
            throw new InvalidArgumentException(
                \sprintf('Name cannot be shorter than %d characters', self::MIN_LENGTH)
            );
        }
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

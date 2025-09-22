<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Curriculum;

use App\Domain\Exception\InvalidArgumentException;

class HoursPerYear
{
    private const MIN_VALUE = 1;
    private const MAX_VALUE = 2555;

    public function __construct(private int $value)
    {
        if ($value < self::MIN_VALUE) {
            throw new InvalidArgumentException(
                \sprintf('Hours per year must be greater than %d', self::MIN_VALUE),
            );
        }

        if ($value > self::MAX_VALUE) {
            throw new InvalidArgumentException(
                \sprintf('Hours per year must be less than %d', self::MAX_VALUE),
            );
        }
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}


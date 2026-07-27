<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Shared;

use App\Domain\Exception\InvalidArgumentException;

final class Uuid
{
    private const PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

    private readonly string $value;

    public function __construct(string $value)
    {
        if (!preg_match(self::PATTERN, $value)) {
            throw new InvalidArgumentException(\sprintf('"%s" is not a valid UUID', $value));
        }

        $this->value = strtolower($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    // RFC 9562 UUIDv7: 48-bit unix ms timestamp + 12-bit and 62-bit randomness.
    public static function generate(): self
    {
        $timeBytes = substr(pack('J', (int) floor(microtime(true) * 1000.0)), -6);
        $rand = random_bytes(10);

        $bytes = $timeBytes
            . chr((ord($rand[0]) & 0x0F) | 0x70)
            . $rand[1]
            . chr((ord($rand[2]) & 0x3F) | 0x80)
            . substr($rand, 3, 7);

        $hex = bin2hex($bytes);

        return new self(\sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        ));
    }

    public function toString(): string
    {
        return $this->value;
    }

    // Crockford base32 (26 chars), same encoding as Symfony\Component\Uid\AbstractUid::toBase32().
    public function toBase32(): string
    {
        $hex = str_replace('-', '', $this->value);

        $base32 = \sprintf(
            '%02s%04s%04s%04s%04s%04s%04s',
            base_convert(substr($hex, 0, 2), 16, 32),
            base_convert(substr($hex, 2, 5), 16, 32),
            base_convert(substr($hex, 7, 5), 16, 32),
            base_convert(substr($hex, 12, 5), 16, 32),
            base_convert(substr($hex, 17, 5), 16, 32),
            base_convert(substr($hex, 22, 5), 16, 32),
            base_convert(substr($hex, 27, 5), 16, 32)
        );

        return strtr($base32, 'abcdefghijklmnopqrstuv', 'ABCDEFGHJKMNPQRSTVWXYZ');
    }
}

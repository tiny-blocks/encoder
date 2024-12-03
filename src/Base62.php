<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use TinyBlocks\Encoder\Internal\Decimal;
use TinyBlocks\Encoder\Internal\Exceptions\InvalidDecoding;
use TinyBlocks\Encoder\Internal\Hexadecimal;

final readonly class Base62 implements Encoder
{
    public const int BASE62_RADIX = 62;
    private const int BASE62_CHARACTER_LENGTH = 1;

    private const string BASE62_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    private function __construct(private string $value)
    {
    }

    public static function from(string $value): Encoder
    {
        return new Base62(value: $value);
    }

    public function encode(): string
    {
        $hexadecimal = Hexadecimal::fromBinary(binary: $this->value, alphabet: self::BASE62_ALPHABET);
        $hexadecimal = $hexadecimal->removeLeadingZeroBytes();

        $base62 = str_repeat(self::BASE62_ALPHABET[0], $hexadecimal->getBytes());

        if ($hexadecimal->isEmpty()) {
            return $base62;
        }

        $base62Value = $hexadecimal->toBase(base: self::BASE62_RADIX);

        return sprintf('%s%s', $base62, $base62Value);
    }

    public function decode(): string
    {
        if (strlen($this->value) !== strspn($this->value, self::BASE62_ALPHABET)) {
            throw new InvalidDecoding(value: $this->value);
        }

        $bytes = 0;
        $value = $this->value;

        while (!empty($value) && str_starts_with($value, self::BASE62_ALPHABET[0])) {
            $bytes++;
            $value = substr($value, self::BASE62_CHARACTER_LENGTH);
        }

        if (empty($value)) {
            return str_repeat("\x00", $bytes);
        }

        $decimal = Decimal::fromBase62(number: $value, alphabet: self::BASE62_ALPHABET);
        $hexadecimal = Hexadecimal::from(value: $decimal->toHexadecimal())
            ->fillWithZeroIfNecessary()
            ->toString();

        $binary = hex2bin($hexadecimal);

        return sprintf('%s%s', str_repeat("\x00", $bytes), $binary);
    }
}

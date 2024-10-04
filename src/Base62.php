<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use TinyBlocks\Encoder\Internal\Exceptions\InvalidDecoding;
use TinyBlocks\Encoder\Internal\Hexadecimal;

final readonly class Base62 implements Encoder
{
    private const BASE62_RADIX = 62;
    private const BASE62_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    private const BASE62_CHARACTER_LENGTH = 1;
    private const BASE62_HEXADECIMAL_RADIX = 16;

    private function __construct(private string $value)
    {
    }

    public static function from(string $value): Encoder
    {
        return new Base62(value: $value);
    }

    public function encode(): string
    {
        $hexadecimal = Hexadecimal::fromBinary(binary: $this->value);
        $bytes = $hexadecimal->removeLeadingZeroBytes();

        $base62 = str_repeat(self::BASE62_ALPHABET[0], $bytes);

        if ($hexadecimal->isEmpty()) {
            return $base62;
        }

        $number = $hexadecimal->toGmpInit(base: self::BASE62_HEXADECIMAL_RADIX);

        return sprintf('%s%s', $base62, gmp_strval($number, self::BASE62_RADIX));
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

        $number = gmp_init($value, self::BASE62_RADIX);
        $hexadecimal = Hexadecimal::fromGmp(number: $number, base: self::BASE62_HEXADECIMAL_RADIX);
        $hexadecimal->padLeft();

        $binary = hex2bin(sprintf('%s%s', str_repeat('00', $bytes), $hexadecimal->toString()));

        if (!is_string($binary)) {
            throw new InvalidDecoding(value: $this->value);
        }

        return $binary;
    }
}

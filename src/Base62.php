<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use TinyBlocks\Encoder\Internal\Decimal;
use TinyBlocks\Encoder\Internal\Exceptions\InvalidDecoding;
use TinyBlocks\Encoder\Internal\Hexadecimal;

final readonly class Base62 implements Encoder
{
    public const string BASE62_RADIX = '62';
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

        $prefix = str_repeat(self::BASE62_ALPHABET[0], $hexadecimal->getBytes());

        if ($hexadecimal->isEmpty()) {
            if ($hexadecimal->getBytes() === 0) {
                return '';
            }

            return sprintf('%s%s', $prefix, self::BASE62_ALPHABET[0]);
        }

        $base62Value = $hexadecimal->toBase(base: self::BASE62_RADIX);

        return sprintf('%s%s', $prefix, $base62Value);
    }


    public function decode(): string
    {
        if (strlen($this->value) !== strspn($this->value, self::BASE62_ALPHABET)) {
            throw new InvalidDecoding(value: $this->value);
        }

        $value = $this->value;

        if ($value === '') {
            return '';
        }

        $leadingZeroCharacters = strspn($value, self::BASE62_ALPHABET[0]);

        if ($leadingZeroCharacters === strlen($value)) {
            return str_repeat("\x00", max(0, $leadingZeroCharacters - 1));
        }

        $bytes = $leadingZeroCharacters;
        $number = ltrim($value, self::BASE62_ALPHABET[0]);

        $decimal = Decimal::from(number: $number, alphabet: self::BASE62_ALPHABET, baseRadix: self::BASE62_RADIX);
        $hexadecimal = Hexadecimal::from(value: $decimal->toHexadecimal())
            ->fillWithZeroIfNecessary()
            ->toString();

        $binary = hex2bin($hexadecimal);

        return sprintf('%s%s', str_repeat("\x00", $bytes), $binary);
    }
}

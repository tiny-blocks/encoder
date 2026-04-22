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

    private function __construct(private string $payload)
    {
    }

    public static function from(string $value): Encoder
    {
        return new Base62(payload: $value);
    }

    public function encode(): string
    {
        $hexadecimal = Hexadecimal::fromBinary(binary: $this->payload, alphabet: self::BASE62_ALPHABET);
        $hexadecimal = $hexadecimal->removeLeadingZeroBytes();

        $prefix = str_repeat(self::BASE62_ALPHABET[0], $hexadecimal->bytes());

        if ($hexadecimal->isEmpty()) {
            if ($hexadecimal->bytes() === 0) {
                return '';
            }

            return sprintf('%s%s', $prefix, self::BASE62_ALPHABET[0]);
        }

        $base62Value = $hexadecimal->toBase(base: self::BASE62_RADIX);

        return sprintf('%s%s', $prefix, $base62Value);
    }

    public function decode(): string
    {
        if (strlen($this->payload) !== strspn($this->payload, self::BASE62_ALPHABET)) {
            throw new InvalidDecoding(value: $this->payload);
        }

        if ($this->payload === '') {
            return '';
        }

        $leadingZeroCharacters = strspn($this->payload, self::BASE62_ALPHABET[0]);

        if ($leadingZeroCharacters === strlen($this->payload)) {
            return str_repeat("\x00", max(0, $leadingZeroCharacters - 1));
        }

        $leadingZeroByteCount = $leadingZeroCharacters;
        $encodedDigits = ltrim($this->payload, self::BASE62_ALPHABET[0]);

        $decimal = Decimal::from(
            number: $encodedDigits,
            alphabet: self::BASE62_ALPHABET,
            baseRadix: self::BASE62_RADIX
        );
        $hexadecimal = Hexadecimal::from(value: $decimal->toHexadecimal())
            ->fillWithZeroIfNecessary()
            ->toString();

        $binary = hex2bin($hexadecimal);

        return sprintf('%s%s', str_repeat("\x00", $leadingZeroByteCount), $binary);
    }
}

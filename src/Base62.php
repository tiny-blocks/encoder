<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use TinyBlocks\Encoder\Exceptions\InvalidDecoding;
use TinyBlocks\Encoder\Internal\Decimal;
use TinyBlocks\Encoder\Internal\Hexadecimal;

/**
 * Base62 encoder and decoder backed by a fixed 62-character alphabet.
 */
final readonly class Base62 implements Encoder
{
    private const string BASE62_RADIX = '62';
    private const string BASE62_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    private function __construct(private string $payload)
    {
    }

    /**
     * Creates a Base62 encoder for the given value.
     *
     * @param string $value The raw value to encode, or the Base62 string to decode.
     * @return Encoder The Base62 encoder.
     */
    public static function from(string $value): Encoder
    {
        return new Base62(payload: $value);
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
            return str_repeat("\x00", ($leadingZeroCharacters - 1));
        }

        $decimal = Decimal::from(
            number: $this->payload,
            alphabet: self::BASE62_ALPHABET,
            baseRadix: self::BASE62_RADIX
        );
        $hexadecimal = Hexadecimal::from(value: $decimal->toHexadecimal())
            ->fillWithZeroIfNecessary()
            ->toString();

        $binary = hex2bin($hexadecimal);

        return sprintf('%s%s', str_repeat("\x00", $leadingZeroCharacters), $binary);
    }

    public function encode(): string
    {
        if ($this->payload === '') {
            return '';
        }

        $hexadecimal = Hexadecimal::fromBinary(binary: $this->payload, alphabet: self::BASE62_ALPHABET);
        $prefix = str_repeat(self::BASE62_ALPHABET[0], $hexadecimal->leadingZeroBytes());

        return sprintf('%s%s', $prefix, $hexadecimal->toBase(base: self::BASE62_RADIX));
    }
}

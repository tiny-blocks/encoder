<?php

namespace TinyBlocks\Encoder;

use TinyBlocks\Encoder\Internal\Exceptions\InvalidBase62Encoding;

final class Base62
{
    private const BASE62_RADIX = 62;
    private const BASE62_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    private const BASE62_HEXADECIMAL_RADIX = 16;

    public static function encode(string $value): string
    {
        $bytes = 0;
        $hexadecimal = bin2hex($value);

        while (str_starts_with($hexadecimal, '00')) {
            $bytes++;
            $hexadecimal = substr($hexadecimal, 2);
        }

        $base62 = str_repeat(self::BASE62_ALPHABET[0], $bytes);

        if (empty($hexadecimal)) {
            return $base62;
        }

        $number = gmp_init($hexadecimal, self::BASE62_HEXADECIMAL_RADIX);

        return $base62 . gmp_strval($number, self::BASE62_RADIX);
    }

    public static function decode(string $value): string
    {
        if (strlen($value) !== strspn($value, self::BASE62_ALPHABET)) {
            throw new InvalidBase62Encoding(value: $value);
        }

        $bytes = 0;

        while (!empty($value) && str_starts_with($value, self::BASE62_ALPHABET[0])) {
            $bytes++;
            $value = substr($value, 1);
        }

        if (empty($value)) {
            return str_repeat("\x00", $bytes);
        }

        $number = gmp_init($value, self::BASE62_RADIX);
        $hexadecimal = gmp_strval($number, self::BASE62_HEXADECIMAL_RADIX);

        if (strlen($hexadecimal) % 2) {
            $hexadecimal = '0' . $hexadecimal;
        }

        return hex2bin(str_repeat('00', $bytes) . $hexadecimal);
    }
}

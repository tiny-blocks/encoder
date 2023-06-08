<?php

namespace TinyBlocks\Encoder;

final class Base62
{
    private const BASE62_RADIX = 62;
    private const BASE62_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    private const HEXADECIMAL_RADIX = 16;

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
            return strtr($base62, self::BASE62_ALPHABET, self::BASE62_ALPHABET);
        }

        $base62Conversion = gmp_strval(gmp_init($hexadecimal, self::HEXADECIMAL_RADIX), self::BASE62_RADIX);

        return strtr($base62 . $base62Conversion, self::BASE62_ALPHABET, self::BASE62_ALPHABET);
    }

    public static function decode(string $value): string
    {
        $bytes = 0;

        while (!empty($value) && str_starts_with($value, self::BASE62_ALPHABET[0])) {
            $bytes++;
            $value = substr($value, 1);
        }

        if (empty($value)) {
            return str_repeat("\x00", $bytes);
        }

        $hexadecimal = gmp_strval(gmp_init($value, self::BASE62_RADIX), self::HEXADECIMAL_RADIX);

        if (strlen($hexadecimal) % 2 !== 0) {
            $hexadecimal = '0' . $hexadecimal;
        }

        return hex2bin(str_repeat('00', $bytes) . $hexadecimal);
    }
}

<?php

namespace TinyBlocks\Encoder;

final class Base62
{
    private const BASE62_RADIX = 62;
    private const BASE62_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    private const HEXADECIMAL_RADIX = 16;

    public static function encode(string $value): string
    {
        if (empty($value)) {
            return $value;
        }

        $hexadecimal = bin2hex($value);
        $decimal = gmp_init($hexadecimal, self::HEXADECIMAL_RADIX);
        $encoded = '';

        while (gmp_cmp($decimal, '0') > 0) {
            $remainder = gmp_intval(gmp_mod($decimal, self::BASE62_RADIX));
            $decimal = gmp_div_q($decimal, self::BASE62_RADIX);
            $encoded = self::BASE62_ALPHABET[$remainder] . $encoded;
        }

        return $encoded;
    }

    public static function decode(string $value): string
    {
        if (empty($value)) {
            return $value;
        }

        $decimal = gmp_init('0');

        for ($i = 0, $length = strlen($value); $i < $length; $i++) {
            $character = $value[$i];
            $position = strpos(self::BASE62_ALPHABET, $character);
            $decimal = gmp_mul($decimal, self::BASE62_RADIX);
            $decimal = gmp_add($decimal, $position);
        }

        $hexadecimal = gmp_strval($decimal, self::HEXADECIMAL_RADIX);
        $decoded = hex2bin($hexadecimal);

        return $decoded !== false ? $decoded : '';
    }
}

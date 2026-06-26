<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

final class BaseConverter
{
    private function __construct()
    {
    }

    public static function toDecimal(string $radix, string $number, string $alphabet): string
    {
        $decimalValue = '0';

        foreach (str_split($number) as $character) {
            $digit = (string)strpos($alphabet, $character);
            $decimalValue = bcmul($decimalValue, $radix);
            $decimalValue = bcadd($decimalValue, $digit);
        }

        return $decimalValue;
    }

    public static function fromDecimal(string $radix, string $alphabet, string $decimalValue): string
    {
        $encoded = '';

        while ($decimalValue !== '0') {
            $remainder = intval(bcmod($decimalValue, $radix));
            $encoded = sprintf('%s%s', $alphabet[$remainder], $encoded);
            $decimalValue = bcdiv($decimalValue, $radix);
        }

        return $encoded;
    }
}

<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

final readonly class BaseConverter
{
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
        $result = '';

        while ($decimalValue !== '0') {
            $remainder = intval(bcmod($decimalValue, $radix));
            $result = sprintf('%s%s', $alphabet[$remainder], $result);
            $decimalValue = bcdiv($decimalValue, $radix);
        }

        return $result;
    }
}

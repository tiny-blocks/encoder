<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

final readonly class Decimal
{
    private function __construct(private string $value)
    {
    }

    public static function from(string $number, string $alphabet, string $baseRadix): Decimal
    {
        $value = '0';
        $length = strlen($number);

        for ($index = 0; $index < $length; $index++) {
            $digit = (string)strpos($alphabet, $number[$index]);
            $value = bcmul($value, $baseRadix);
            $value = bcadd($value, $digit);
        }

        return new Decimal(value: $value);
    }

    public function toHexadecimal(): string
    {
        $value = $this->value;
        $hexadecimalValue = '';

        while (bccomp($value, '0') > 0) {
            $remainder = (int)bcmod($value, Hexadecimal::HEXADECIMAL_RADIX);
            $hexadecimalValue = sprintf('%s%s', Hexadecimal::HEXADECIMAL_ALPHABET[$remainder], $hexadecimalValue);
            $value = bcdiv($value, Hexadecimal::HEXADECIMAL_RADIX);
        }

        return $hexadecimalValue;
    }
}

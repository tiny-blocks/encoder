<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

use TinyBlocks\Encoder\Base62;

final readonly class Decimal
{
    private function __construct(private string $value)
    {
    }

    public static function fromBase62(string $number, string $alphabet): Decimal
    {
        $value = '0';
        $length = strlen($number);

        for ($index = 0; $index < $length; $index++) {
            $digit = strpos($alphabet, $number[$index]);
            $value = bcmul($value, (string)Base62::BASE62_RADIX);
            $value = bcadd($value, (string)$digit);
        }

        return new Decimal(value: $value);
    }

    public function toHexadecimal(): string
    {
        $value = $this->value;
        $hexadecimalValue = '';

        while (bccomp($value, '0') > 0) {
            $remainder = bcmod($value, Hexadecimal::HEXADECIMAL_RADIX);
            $hexadecimalValue = sprintf('%s%s', Hexadecimal::HEXADECIMAL_ALPHABET[(int)$remainder], $hexadecimalValue);
            $value = bcdiv($value, Hexadecimal::HEXADECIMAL_RADIX);
        }

        return $hexadecimalValue;
    }
}

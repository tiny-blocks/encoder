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
        $value = BaseConverter::toDecimal(radix: $baseRadix, number: $number, alphabet: $alphabet);

        return new Decimal(value: $value);
    }

    public function toHexadecimal(): string
    {
        return BaseConverter::fromDecimal(
            radix: Hexadecimal::HEXADECIMAL_RADIX,
            alphabet: Hexadecimal::HEXADECIMAL_ALPHABET,
            decimalValue: $this->value
        );
    }
}

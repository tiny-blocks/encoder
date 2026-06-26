<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

final readonly class Hexadecimal
{
    public const string HEXADECIMAL_RADIX = '16';
    public const string HEXADECIMAL_ALPHABET = '0123456789abcdef';
    private const int HEXADECIMAL_BYTE_LENGTH = 2;

    private function __construct(private string $value, private string $alphabet)
    {
    }

    public static function from(string $value): Hexadecimal
    {
        return new Hexadecimal(value: $value, alphabet: self::HEXADECIMAL_ALPHABET);
    }

    public static function fromBinary(string $binary, string $alphabet): Hexadecimal
    {
        return new Hexadecimal(value: bin2hex($binary), alphabet: $alphabet);
    }

    public function toBase(string $base): string
    {
        $decimalValue = BaseConverter::toDecimal(
            radix: self::HEXADECIMAL_RADIX,
            number: $this->value,
            alphabet: self::HEXADECIMAL_ALPHABET
        );

        $baseValue = BaseConverter::fromDecimal(radix: $base, alphabet: $this->alphabet, decimalValue: $decimalValue);

        return $baseValue ?: '0';
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function leadingZeroBytes(): int
    {
        $leadingZeroCharacters = strspn($this->value, '0');
        $offset = $leadingZeroCharacters - ($leadingZeroCharacters % self::HEXADECIMAL_BYTE_LENGTH);

        return intdiv($offset, self::HEXADECIMAL_BYTE_LENGTH);
    }

    public function fillWithZeroIfNecessary(): Hexadecimal
    {
        $newValue = strlen($this->value) % 2 !== 0 ? sprintf('0%s', $this->value) : $this->value;

        return new Hexadecimal(value: $newValue, alphabet: $this->alphabet);
    }
}

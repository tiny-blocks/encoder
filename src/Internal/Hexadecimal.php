<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

final readonly class Hexadecimal
{
    public const string HEXADECIMAL_RADIX = '16';
    private const int DEFAULT_BYTE_COUNT = 0;
    public const string HEXADECIMAL_ALPHABET = '0123456789abcdef';
    private const int HEXADECIMAL_BYTE_LENGTH = 2;

    private function __construct(
        private string $value,
        private string $alphabet,
        private int $bytes = self::DEFAULT_BYTE_COUNT
    ) {
    }

    public static function from(string $value): Hexadecimal
    {
        return new Hexadecimal(value: $value, alphabet: self::HEXADECIMAL_ALPHABET);
    }

    public static function fromBinary(string $binary, string $alphabet): Hexadecimal
    {
        return new Hexadecimal(value: bin2hex($binary), alphabet: $alphabet);
    }

    public function bytes(): int
    {
        return $this->bytes;
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function fillWithZeroIfNecessary(): Hexadecimal
    {
        $newValue = strlen($this->value) % 2 !== 0 ? sprintf('0%s', $this->value) : $this->value;

        return new Hexadecimal(value: $newValue, alphabet: $this->alphabet, bytes: $this->bytes);
    }

    public function removeLeadingZeroBytes(): Hexadecimal
    {
        $value = $this->value;

        $leadingZeroCharacters = strspn($value, '0');
        $offset = $leadingZeroCharacters - ($leadingZeroCharacters % self::HEXADECIMAL_BYTE_LENGTH);

        $bytes = intdiv($offset, self::HEXADECIMAL_BYTE_LENGTH);

        if ($offset === strlen($value)) {
            $value = '';
        }

        return new Hexadecimal(value: $value, alphabet: $this->alphabet, bytes: $bytes);
    }

    public function toBase(string $base): string
    {
        $decimalValue = BaseConverter::toDecimal(
            radix: self::HEXADECIMAL_RADIX,
            number: $this->value,
            alphabet: self::HEXADECIMAL_ALPHABET
        );

        $result = BaseConverter::fromDecimal(radix: $base, alphabet: $this->alphabet, decimalValue: $decimalValue);

        return $result ?: '0';
    }
}

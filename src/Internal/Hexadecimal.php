<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

final readonly class Hexadecimal
{
    private const int DEFAULT_BYTE_COUNT = 0;
    private const int HEXADECIMAL_BYTE_LENGTH = 2;

    public const string HEXADECIMAL_RADIX = '16';
    public const string HEXADECIMAL_ALPHABET = '0123456789abcdef';

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

    public function removeLeadingZeroBytes(): Hexadecimal
    {
        $bytes = 0;
        $newValue = $this->value;

        while (str_starts_with($newValue, '00')) {
            $bytes++;
            $newValue = substr($newValue, self::HEXADECIMAL_BYTE_LENGTH);
        }

        return new Hexadecimal(value: $newValue, alphabet: $this->alphabet, bytes: $bytes);
    }

    public function fillWithZeroIfNecessary(): Hexadecimal
    {
        $newValue = strlen($this->value) % 2 !== 0 ? sprintf('0%s', $this->value) : $this->value;

        return new Hexadecimal(value: $newValue, alphabet: $this->alphabet, bytes: $this->bytes);
    }

    public function getBytes(): int
    {
        return $this->bytes;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function toBase(string $base): string
    {
        $length = strlen($this->value);
        $decimalValue = '0';

        for ($index = 0; $index < $length; $index++) {
            $digit = (string)strpos(self::HEXADECIMAL_ALPHABET, $this->value[$index]);
            $decimalValue = bcmul($decimalValue, self::HEXADECIMAL_RADIX);
            $decimalValue = bcadd($decimalValue, $digit);
        }

        $digits = $this->alphabet;
        $result = '';

        while (bccomp($decimalValue, '0') > 0) {
            $remainder = (int)bcmod($decimalValue, $base);
            $result = sprintf('%s%s', $digits[$remainder], $result);
            $decimalValue = bcdiv($decimalValue, $base);
        }

        return $result ?: '0';
    }

    public function toString(): string
    {
        return $this->value;
    }
}

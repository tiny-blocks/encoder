<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal;

use GMP;

final class Hexadecimal
{
    private const HEXADECIMAL_BYTE_LENGTH = 2;

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromGmp(GMP $number, int $base): Hexadecimal
    {
        return new Hexadecimal(value: gmp_strval($number, $base));
    }

    public static function fromBinary(string $binary): Hexadecimal
    {
        return new Hexadecimal(value: bin2hex($binary));
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function padLeft(): void
    {
        if (strlen($this->value) % 2 !== 0) {
            $this->value = sprintf('0%s', $this->value);
        }
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toGmpInit(int $base): GMP
    {
        return gmp_init($this->value, $base);
    }

    public function removeLeadingZeroBytes(): int
    {
        $bytes = 0;

        while (str_starts_with($this->value, '00')) {
            $bytes++;
            $this->value = substr($this->value, self::HEXADECIMAL_BYTE_LENGTH);
        }

        return $bytes;
    }
}

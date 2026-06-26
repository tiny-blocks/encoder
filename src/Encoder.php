<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use TinyBlocks\Encoder\Exceptions\InvalidDecoding;

/**
 * Defines a contract for encoding and decoding data.
 */
interface Encoder
{
    /**
     * Decodes the current encoded value back to its original form.
     *
     * @return string The decoded value.
     * @throws InvalidDecoding If decoding fails.
     */
    public function decode(): string;

    /**
     * Encodes the current value into a specific format.
     *
     * @return string The encoded value.
     */
    public function encode(): string;
}

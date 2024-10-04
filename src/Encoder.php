<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use TinyBlocks\Encoder\Internal\Exceptions\InvalidDecoding;

/**
 * Define a contract for encoding and decoding data.
 */
interface Encoder
{
    /**
     * Encodes the current value into a specific format.
     *
     * @return string The encoded value.
     */
    public function encode(): string;

    /**
     * Decodes the current encoded value back to its original form.
     *
     * @return string The decoded value.
     * @throws InvalidDecoding if decoding fails.
     */
    public function decode(): string;
}

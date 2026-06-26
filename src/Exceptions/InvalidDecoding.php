<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Exceptions;

use RuntimeException;

/**
 * Raised when a value cannot be decoded.
 */
final class InvalidDecoding extends RuntimeException
{
    public function __construct(string $value)
    {
        $template = 'The value <%s> could not be decoded.';

        parent::__construct(message: sprintf($template, $value));
    }
}

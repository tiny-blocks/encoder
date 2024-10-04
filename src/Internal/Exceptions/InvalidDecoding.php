<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder\Internal\Exceptions;

use RuntimeException;

final class InvalidDecoding extends RuntimeException
{
    public function __construct(private readonly string $value)
    {
        $template = 'The value <%s> could not be decoded.';
        parent::__construct(message: sprintf($template, $this->value));
    }
}

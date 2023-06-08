<?php

namespace TinyBlocks\Encoder\Internal\Exceptions;

use RuntimeException;

final class InvalidBase62Encoding extends RuntimeException
{
    public function __construct(private readonly string $value)
    {
        $template = 'The value <%s> does not have a valid base62 encoding.';
        parent::__construct(message: sprintf($template, $this->value));
    }
}

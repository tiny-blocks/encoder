<?php

declare(strict_types=1);

namespace Test\TinyBlocks\Encoder;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use TinyBlocks\Encoder\Internal\BaseConverter;

final class BaseConverterTest extends TestCase
{
    public function testConstructorWhenInvokedViaReflectionThenInstanceIsCreated(): void
    {
        /** @Given the private constructor of the static-only base converter */
        $constructor = new ReflectionMethod(objectOrMethod: BaseConverter::class, method: '__construct');

        /** @And an instance allocated without invoking the constructor */
        $instance = $constructor->getDeclaringClass()->newInstanceWithoutConstructor();

        /** @When the private constructor is invoked */
        $constructor->invoke($instance);

        /** @Then a base converter instance is created */
        self::assertInstanceOf(BaseConverter::class, $instance);
    }
}

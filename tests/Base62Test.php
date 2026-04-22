<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Encoder\Internal\Exceptions\InvalidDecoding;

final class Base62Test extends TestCase
{
    #[DataProvider('providerForEncoding')]
    public function testWhenEncodingThenReturnsBase62Representation(string $value, string $expected): void
    {
        /** @Given a raw string value */
        $payload = $value;

        /** @When encoding the payload using Base62 */
        $actual = Base62::from(value: $payload)->encode();

        /** @Then the encoded representation matches the expected Base62 string */
        self::assertEquals($expected, $actual);
    }

    #[DataProvider('providerForDecoding')]
    public function testWhenDecodingThenReturnsOriginalValue(string $value, string $expected): void
    {
        /** @Given a Base62 encoded string */
        $encoded = $value;

        /** @When decoding the encoded string using Base62 */
        $actual = Base62::from(value: $encoded)->decode();

        /** @Then the decoded value matches the original payload */
        self::assertEquals($expected, $actual);
    }

    #[DataProvider('providerForRoundTripWithAllZeroBytes')]
    public function testWhenRoundTrippingAllZeroBytesThenReturnsOriginalPayload(string $value): void
    {
        /** @Given a binary payload containing only zero bytes */
        $payload = $value;

        /** @When round-tripping the payload through encode and decode */
        $decoded = Base62::from(value: Base62::from(value: $payload)->encode())->decode();

        /** @Then the decoded payload matches the original binary payload */
        self::assertEquals($payload, $decoded);
    }

    #[DataProvider('providerForRoundTripWithLeadingZeroBytes')]
    public function testWhenRoundTrippingPayloadWithLeadingZeroBytesThenReturnsOriginalPayload(string $value): void
    {
        /** @Given a binary payload with leading zero bytes */
        $payload = $value;

        /** @When round-tripping the payload through encode and decode */
        $decoded = Base62::from(value: Base62::from(value: $payload)->encode())->decode();

        /** @Then the decoded payload matches the original binary payload */
        self::assertEquals($payload, $decoded);
    }

    public function testWhenDecodingValueWithCharactersOutsideAlphabetThenInvalidDecodingIsThrown(): void
    {
        /** @Given a binary payload containing characters outside the Base62 alphabet */
        $payload = hex2bin('9850EEEC191BF4FF26F99315CE43B0C8');

        /** @Then an InvalidDecoding exception describing the payload should be thrown */
        $this->expectException(InvalidDecoding::class);
        $this->expectExceptionMessage(sprintf('The value <%s> could not be decoded.', $payload));

        /** @When attempting to decode the invalid payload */
        Base62::from(value: $payload)->decode();
    }

    public function testWhenDecodingValueWithBackslashThenInvalidDecodingIsThrown(): void
    {
        /** @Given a payload containing a backslash that is not in the Base62 alphabet */
        $payload = '\\A';

        /** @Then an InvalidDecoding exception describing the payload should be thrown */
        $this->expectException(InvalidDecoding::class);
        $this->expectExceptionMessage(sprintf('The value <%s> could not be decoded.', $payload));

        /** @When attempting to decode the invalid payload */
        Base62::from(value: $payload)->decode();
    }

    public static function providerForEncoding(): array
    {
        return [
            'Hello world'        => ['value' => 'Hello world!', 'expected' => 'T8dgcjRGuYUueWht'],
            'Empty string'       => ['value' => '', 'expected' => ''],
            'Numeric string'     => ['value' => '1234567890', 'expected' => '1A0afZkibIAR2O'],
            'Special characters' => ['value' => '@#$%^&*()', 'expected' => 'MjehbVgJedVR']
        ];
    }

    public static function providerForDecoding(): array
    {
        return [
            'Zero value'         => ['value' => '0', 'expected' => ''],
            'Empty string'       => ['value' => '', 'expected' => ''],
            'Hello world'        => ['value' => 'T8dgcjRGuYUueWht', 'expected' => 'Hello world!'],
            'Leading zeros'      => ['value' => '000001', 'expected' => hex2bin('000000000001')],
            'Two zero bytes'     => ['value' => '000', 'expected' => "\x00\x00"],
            'Numeric string'     => ['value' => '1A0afZkibIAR2O', 'expected' => '1234567890'],
            'Single zero byte'   => ['value' => '00', 'expected' => "\x00"],
            'Single character'   => ['value' => '1', 'expected' => "\001"],
            'Special characters' => ['value' => 'MjehbVgJedVR', 'expected' => '@#$%^&*()']
        ];
    }

    public static function providerForRoundTripWithAllZeroBytes(): array
    {
        return [
            'Single zero byte' => ['value' => "\x00"],
            'Two zero bytes'   => ['value' => "\x00\x00"],
            'Eight zero bytes' => ['value' => str_repeat("\x00", 8)]
        ];
    }

    public static function providerForRoundTripWithLeadingZeroBytes(): array
    {
        return [
            'Leading zero bytes 01' => ['value' => '001jlt60MnKnB9ECKRt4gl'],
            'Leading zero bytes 02' => ['value' => hex2bin('07d8e31da269bf28')],
            'Leading zero bytes 03' => ['value' => hex2bin('0000010203040506')]
        ];
    }
}

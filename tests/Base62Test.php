<?php

declare(strict_types=1);

namespace TinyBlocks\Encoder;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TinyBlocks\Encoder\Internal\Exceptions\InvalidDecoding;

final class Base62Test extends TestCase
{
    #[DataProvider('providerForTestEncode')]
    public function testEncode(string $value, string $expected): void
    {
        /** @Given a string value to encode */
        $encoder = Base62::from(value: $value);

        /** @When encoding the value using Base62 */
        $actual = $encoder->encode();

        /** @Then the encoded value should match the expected result */
        self::assertEquals($expected, $actual);
    }

    #[DataProvider('providerForTestDecode')]
    public function testDecode(string $value, string $expected): void
    {
        /** @Given a Base62 encoded string */
        $encoder = Base62::from(value: $value);

        /** @When decoding the value using Base62 */
        $actual = $encoder->decode();

        /** @Then the decoded value should match the expected result */
        self::assertEquals($expected, $actual);
    }

    public function testWhenInvalidDecodingBase62(): void
    {
        $value = hex2bin('9850EEEC191BF4FF26F99315CE43B0C8');
        $template = 'The value <%s> could not be decoded.';

        $this->expectException(InvalidDecoding::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        Base62::from(value: $value)->decode();
    }

    public function testWhenInvalidDecodingBase62WhenHex2BinFails(): void
    {
        $value = '\\A';
        $template = 'The value <%s> could not be decoded.';

        $this->expectException(InvalidDecoding::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        Base62::from(value: $value)->decode();
    }

    #[DataProvider('providerForTestEncodeAndDecodeWithLeadingZeroBytes')]
    public function testEncodeAndDecodeWithLeadingZeroBytes(string $value): void
    {
        /** @Given a binary value with leading zero bytes */
        $encoder = Base62::from(value: $value);

        /** @When encoding the binary value */
        $encoded = $encoder->encode();

        /** @When decoding the encoded value */
        $decoded = Base62::from(value: $encoded)->decode();

        /** @Then the decoded value should match the original binary value */
        self::assertEquals($value, $decoded);
    }

    public static function providerForTestEncode(): array
    {
        return [
            'Hello world'        => ['value' => 'Hello world!', 'expected' => 'T8dgcjRGuYUueWht'],
            'Empty string'       => ['value' => '', 'expected' => ''],
            'Numeric string'     => ['value' => '1234567890', 'expected' => '1A0afZkibIAR2O'],
            'Special characters' => ['value' => '@#$%^&*()', 'expected' => 'MjehbVgJedVR']
        ];
    }

    public static function providerForTestDecode(): array
    {
        return [
            'Zero value'         => ['value' => '0', 'expected' => ''],
            'Empty string'       => ['value' => '', 'expected' => ''],
            'Hello world'        => ['value' => 'T8dgcjRGuYUueWht', 'expected' => 'Hello world!'],
            'Leading zeros'      => ['value' => '000001', 'expected' => hex2bin('000000000001')],
            'Numeric string'     => ['value' => '1A0afZkibIAR2O', 'expected' => '1234567890'],
            'Single character'   => ['value' => '1', 'expected' => "\001"],
            'Special characters' => ['value' => 'MjehbVgJedVR', 'expected' => '@#$%^&*()']
        ];
    }

    public static function providerForTestEncodeAndDecodeWithLeadingZeroBytes(): array
    {
        return [
            'Leading zero bytes 01' => ['value' => '001jlt60MnKnB9ECKRt4gl'],
            'Leading zero bytes 02' => ['value' => hex2bin('07d8e31da269bf28')],
            'Leading zero bytes 03' => ['value' => hex2bin('0000010203040506')]
        ];
    }
}

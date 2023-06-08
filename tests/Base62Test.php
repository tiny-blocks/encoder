<?php

namespace TinyBlocks\Encoder;

use PHPUnit\Framework\TestCase;
use TinyBlocks\Encoder\Internal\Exceptions\InvalidBase62Encoding;

class Base62Test extends TestCase
{
    /**
     * @dataProvider providerForTestEncode
     */
    public function testEncode(string $value, string $expected)
    {
        $actual = Base62::encode(value: $value);

        self::assertEquals($expected, $actual);
    }

    /**
     * @dataProvider providerForTestDecode
     */
    public function testDecode(string $value, string $expected)
    {
        $actual = Base62::decode(value: $value);

        self::assertEquals($expected, $actual);
    }

    /**
     * @dataProvider providerForTestEncodeAndDecodeWithLeadingZeroBytes
     */
    public function testEncodeAndDecodeWithLeadingZeroBytes(string $value): void
    {
        $encoded = Base62::encode(value: $value);
        $actual = Base62::decode(value: $encoded);

        self::assertEquals($value, $actual);
    }

    public function testWhenInvalidBase62Encoding(): void
    {
        $value = hex2bin('9850EEEC191BF4FF26F99315CE43B0C8');
        $template = 'The value <%s> does not have a valid base62 encoding.';

        $this->expectException(InvalidBase62Encoding::class);
        $this->expectExceptionMessage(sprintf($template, $value));

        Base62::decode(value: $value);
    }

    public function providerForTestEncode(): array
    {
        return [
            ['', ''],
            ['@#$%^&*()', 'MjehbVgJedVR'],
            ['1234567890', '1A0afZkibIAR2O'],
            ['Hello world!', 'T8dgcjRGuYUueWht']
        ];
    }

    public function providerForTestDecode(): array
    {
        return [
            ['', ''],
            ['MjehbVgJedVR', '@#$%^&*()'],
            ['1A0afZkibIAR2O', '1234567890'],
            ['T8dgcjRGuYUueWht', 'Hello world!']
        ];
    }

    public function providerForTestEncodeAndDecodeWithLeadingZeroBytes(): array
    {
        return [
            ['001jlt60MnKnB9ECKRt4gl'],
            [hex2bin('07d8e31da269bf28')],
            [hex2bin('0000010203040506')]
        ];
    }
}

<?php

namespace TinyBlocks\Encoder;

use PHPUnit\Framework\TestCase;

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

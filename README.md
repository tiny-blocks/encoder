# Encoder

[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

* [Overview](#overview)
* [Installation](#installation)
* [How to use](#how-to-use)
* [License](#license)
* [Contributing](#contributing)

<div id='overview'></div> 

## Overview

Encoder and decoder for arbitrary data.

<div id='installation'></div>

## Installation

```bash
composer require tiny-blocks/encoder
```

<div id='how-to-use'></div>

## How to use

The library provides concrete implementations of the `Encoder` interface, enabling encoding and decoding of data into
specific formats like Base62.

### Using Base62

To encode a value into Base62 format:

```php
$encoder = Base62::from(value: 'Hello world!');
$encoded = $encoder->encode();

# Output: T8dgcjRGuYUueWht
```

To decode a Base62-encoded value back to its original form:

```php
$encoder = Base62::from(value: 'T8dgcjRGuYUueWht');
$decoded = $encoder->decode();

# Output: Hello world!
```

If you attempt to decode an invalid Base62 value, an `InvalidDecoding` exception will be thrown:

```php
try {
    $encoder = Base62::from(value: 'invalid_value');
    $decoded = $encoder->decode();
} catch (InvalidDecoding $exception) {
    echo $exception->getMessage();
    # Output: The value <invalid_value> could not be decoded.
}
```

<div id='license'></div> 

## License

Encoder is licensed under [MIT](LICENSE).

<div id='contributing'></div>

## Contributing

Please follow the [contributing guidelines](https://github.com/tiny-blocks/tiny-blocks/blob/main/CONTRIBUTING.md) to
contribute to the project.

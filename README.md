# Encoder

[![License](https://img.shields.io/badge/license-MIT-green)](https://github.com/tiny-blocks/encoder/blob/main/LICENSE)

* [Overview](#overview)
* [Installation](#installation)
* [How to use](#how-to-use)
    + [Using Base62](#using-base62)
* [License](#license)
* [Contributing](#contributing)

## Overview

Encoder and decoder for arbitrary data.

## Installation

```bash
composer require tiny-blocks/encoder
```

## How to use

The library provides concrete implementations of the `Encoder` interface, enabling encoding and decoding of data into
specific formats like Base62.

### Using Base62

To encode a value into Base62 format:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Encoder\Base62;

$encoder = Base62::from(value: 'Hello world!');
$encoded = $encoder->encode();

# Output: T8dgcjRGuYUueWht
```

To decode a Base62-encoded value back to its original form:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Encoder\Base62;

$encoder = Base62::from(value: 'T8dgcjRGuYUueWht');
$decoded = $encoder->decode();

# Output: Hello world!
```

If you attempt to decode an invalid Base62 value, an `InvalidDecoding` exception will be thrown:

```php
<?php

declare(strict_types=1);

use TinyBlocks\Encoder\Base62;
use TinyBlocks\Encoder\Exceptions\InvalidDecoding;

try {
    $encoder = Base62::from(value: 'invalid_value');
    $decoded = $encoder->decode();
} catch (InvalidDecoding $exception) {
    echo $exception->getMessage();
    # Output: The value <invalid_value> could not be decoded.
}
```

## License

Encoder is licensed under [MIT](LICENSE).

## Contributing

Please follow the [contributing guidelines](https://github.com/tiny-blocks/tiny-blocks/blob/main/CONTRIBUTING.md) to
contribute to the project.

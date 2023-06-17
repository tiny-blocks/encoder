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

The library exposes concrete implementations for encoding and decoding data.

### Using Base62

```php
$encoded = Base62::encode(value: 'Hello world!') # T8dgcjRGuYUueWht

Base62::decode(value: $encoded) # Hello world!
```

<div id='license'></div> 

## License

Encoder is licensed under [MIT](LICENSE).

<div id='contributing'></div>

## Contributing

Please follow the [contributing guidelines](https://github.com/tiny-blocks/tiny-blocks/blob/main/CONTRIBUTING.md) to
contribute to the project.

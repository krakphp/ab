# AB Testing Library

`Krak\AB` is a framework for implementing AB Tests.

## Installation

Install with composer at `krak/ab`

## Usage

```php
<?php

use Krak\AB;

$exp = AB\Experiment::create('flow', [
    // first is the variant name, second is the ratio.
    ['standard', 80],
    ['variant', 20],
]);

$engine = AB\engine([$exp], new Logger()); // any PSR Logger will work
// The default engine is the Hashing engine

foreach (range(1, 10) as $i) {
    //
    echo $engine->activate('flow', 'id_'.$i.rand()) . PHP_EOL;
}
```

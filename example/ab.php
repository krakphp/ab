<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Krak\AB,
    fool\echolog\Echolog;

$exp = AB\Experiment::create('flow', [
    ['standard', 80],
    ['variant', 20],
]);

$engine = AB\engine([$exp], new Echolog());

foreach (range(1, 10) as $i) {
    echo $engine->activate('flow', 'id_'.$i.rand()) . PHP_EOL;
}

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Krak\AB,
    fool\echolog\Echolog;

$exp = AB\Experiment::create('flow', [
    ['standard', 80],
    ['variant', 20],
]);

$engine = AB\engine([$exp], new Echolog());

foreach (explode(',', 'a,b,c,d,e,f,g,h,i') as $c) {
    var_dump($engine->activate('flow', $c. rand()));
}

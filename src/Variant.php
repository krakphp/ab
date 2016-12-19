<?php

namespace Krak\AB;

class Variant {
    public $name;
    public $is_control;
    public $ratio;
    public $meta;

    public function __construct($name, $ratio, $is_control, array $meta = []) {
        $this->name = $name;
        $this->ratio = $ratio;
        $this->is_control = $is_control;
        $this->meta = $meta;
    }

    public static function control($name, $ratio, array $meta = []) {
        return new self($name, $ratio, true, $meta);
    }

    public static function create($name, $ratio, array $meta = []) {
        return new self($name, $ratio, false, $meta);
    }
}

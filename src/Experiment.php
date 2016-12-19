<?php

namespace Krak\AB;

class Experiment {
    public $name;
    public $variants;
    public $meta;

    public function __construct($name, array $variants, array $meta = []) {
        $this->name = $name;
        $this->variants = $variants;
        $this->meta = $meta;
    }

    /** returns the control variant */
    public function getControl() {
        foreach ($this->variations as $variation) {
            if ($variation->is_control) {
                return $variation;
            }
        }
    }

    /** $variants is an array with each element of tuple `[$name, $ratio, $is_control = false, $meta = []]`.
        The last two elements are optional */
    public static function create($name, array $variants, array $meta = []) {
        $variants = array_map(function($tup) {
            list($name, $ratio) = $tup;
            return new Variant(
                $name,
                $ratio,
                isset($tup[2]) ? $tup[2] : false,
                isset($tup[3]) ? $tup[3] : []
            );
        }, $variants);

        return new self($name, $variants, $meta);
    }
}

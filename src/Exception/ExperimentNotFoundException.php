<?php

namespace Krak\AB\Exception;

class ExperimentNotFoundException extends \RuntimeException
{
    private $experiment_name;

    public function __construct($name) {
        parent::__construct("Experiment $name was not found.");
        $this->experiment_name = $name;
    }

    public function getExperimentName() {
        return $this->$experiment_name;
    }
}

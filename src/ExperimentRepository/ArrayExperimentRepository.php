<?php

namespace Krak\AB\ExperimentRepository;

use Krak\AB\ExperimentRepository;

class ArrayExperimentRepository implements ExperimentRepository
{
    private $experiments;

    public function __construct(array $experiments) {
        $this->experiments = $experiments;
    }

    public function findExperimentByName($name) {
        foreach ($this->experiments as $exp) {
            if ($exp->name == $name) {
                return $exp;
            }
        }
    }
}

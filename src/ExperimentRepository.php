<?php

namespace Krak\AB;

interface ExperimentRepository {
    public function findExperimentByName($name);
}

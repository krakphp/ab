<?php

namespace Krak\AB;

/** helper to create a default engine from an experiment set */
function engine(array $experiments, ...$args) {
    $repo = new ExperimentRepository\ArrayExperimentRepository($experiments);
    return new ABEngine\HashingABEngine($repo, ...$args);
}

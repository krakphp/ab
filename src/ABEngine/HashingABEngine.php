<?php

namespace Krak\AB\ABEngine;

use Krak\AB,
    Psr\Log;

class HashingABEngine implements AB\ABEngine
{
    const MAX_HASH_VALUE = 2 ** 32;

    private $repo;
    private $logger;
    private $seed;
    private $cached_experiments;

    /** important to keep `$seed` the same unless you want to reset all of the activations */
    public function __construct(AB\ExperimentRepository $repo, Log\LoggerInterface $logger = null, $seed = 0) {
        $this->repo = $repo;
        $this->logger = $logger ?: new Log\NullLogger();
        $this->seed = $seed;
        $this->cached_experiments = [];
    }

    public function activate($experiment_name, $user_id, array $meta = []) {
        $experiment = $this->repo->findExperimentByName($experiment_name);

        if (!$experiment) {
            $this->logger->error('Experiment {exp} not found', ['exp' => $experiment_name]);
            throw new AB\Exception\ExperimentNotFoundException($experiment_name);
        }

        $this->logger->info('Activating experiment {exp} for user {uid} with scope: {scope}', [
            'exp' => $experiment_name,
            'uid' => $user_id,
            'scope' => json_encode($meta),
        ]);

        $variants = $this->normalizeExperiment($experiment);

        list(, $max_end_of_range) = end($variants);

        $this->logger->debug('Max end of range {eor} for exp {exp} and user {uid}', [
            'eor' => $max_end_of_range,
            'exp' => $experiment_name,
            'uid' => $user_id,
        ]);

        $hash = murmurhash3_int($experiment_name.$user_id, $this->seed);
        $ratio = $hash / self::MAX_HASH_VALUE;
        $slot = $ratio * $max_end_of_range;

        $this->logger->debug('Experiment {exp} and user {uid} hashed to {hash} and slot {slot}', [
            'exp' => $experiment_name,
            'uid' => $user_id,
            'hash' => $hash,
            'slot' => $slot,
        ]);

        foreach ($variants as list($variation, $end_of_range)) {
            if ($slot <= $end_of_range) {
                return $variation;
            }
        }

        $this->logger->notice('No variants found in range for Experiment {exp} and user {uid}, using last variant instead', [
            'exp' => $experiment_name,
            'uid' => $user_id,
        ]);

        return end($variants)[0];
    }

    private function normalizeExperiment(AB\Experiment $exp) {
        if (isset($this->cached_experiments[$exp->name])) {
            return $this->cached_experiments[$exp->name];
        }

        return array_reduce($exp->variants, function($acc, $v) {
            list($variants, $end_of_range) = $acc;
            $end_of_range += $v->ratio;
            $variants[] = [$v->name, $end_of_range];
            return [$variants, $end_of_range];
        }, [[], 0])[0];
    }
}

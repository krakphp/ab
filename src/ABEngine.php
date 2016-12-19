<?php

namespace Krak\AB;

/** AB Engine is the utility that actually performs the AB calculation for a user id */
interface ABEngine {
    /** @return string $variation returns the variation key. */
    public function activate($expirement, $user_id, array $meta = []);
}

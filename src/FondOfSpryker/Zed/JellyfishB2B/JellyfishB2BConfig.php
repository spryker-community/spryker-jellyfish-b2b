<?php

namespace FondOfSpryker\Zed\JellyfishB2B;

use FondOfSpryker\Shared\JellyfishB2B\JellyfishB2BConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class JellyfishB2BConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->get(JellyfishB2BConstants::BASE_URI, 'http://localhost');
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->get(JellyfishB2BConstants::TIMEOUT, 2.0);
    }

    /**
     * @return bool
     */
    public function dryRun(): bool
    {
        return $this->get(JellyfishB2BConstants::DRY_RUN, true);
    }
}

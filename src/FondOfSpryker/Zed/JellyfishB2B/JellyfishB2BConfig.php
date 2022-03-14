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
        return $this->get(
            JellyfishB2BConstants::BASE_URI,
            JellyfishB2BConstants::BASE_URI_DEFAULT,
        );
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->get(
            JellyfishB2BConstants::TIMEOUT,
            JellyfishB2BConstants::TIMEOUT_DEFAULT,
        );
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->get(
            JellyfishB2BConstants::USERNAME,
            JellyfishB2BConstants::USERNAME_DEFAULT,
        );
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->get(
            JellyfishB2BConstants::PASSWORD,
            JellyfishB2BConstants::PASSWORD_DEFAULT,
        );
    }

    /**
     * @return bool
     */
    public function dryRun(): bool
    {
        return $this->get(
            JellyfishB2BConstants::DRY_RUN,
            JellyfishB2BConstants::DRY_RUN_DEFAULT,
        );
    }
}

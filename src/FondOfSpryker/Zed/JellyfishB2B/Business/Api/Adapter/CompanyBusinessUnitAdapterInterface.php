<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter;

use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Psr\Http\Message\StreamInterface;

interface CompanyBusinessUnitAdapterInterface
{
    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function sendRequest(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): ?StreamInterface;
}

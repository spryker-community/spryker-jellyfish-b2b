<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter;

use Psr\Http\Message\StreamInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface AdapterInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $request
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function sendRequest(AbstractTransfer $request): ?StreamInterface;
}

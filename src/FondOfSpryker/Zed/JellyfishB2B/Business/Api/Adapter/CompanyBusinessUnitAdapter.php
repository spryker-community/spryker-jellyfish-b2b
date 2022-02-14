<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AbstractAdapter;
use Psr\Http\Message\ResponseInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CompanyBusinessUnitAdapter extends AbstractAdapter
{
    /**
     * @var string
     */
    protected const COMPANY_BUSINESS_UNITS_URI = 'standard/company-business-units';

    /**
     * @return string
     */
    protected function getUri(): string
    {
        return static::COMPANY_BUSINESS_UNITS_URI;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return void
     */
    protected function handleResponse(ResponseInterface $response, AbstractTransfer $transfer): void
    {
    }
}

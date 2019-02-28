<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter;

use Psr\Http\Message\ResponseInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CompanyUserAdapter extends AbstractAdapter
{
    protected const COMPANY_USERS_URI = 'standard/company-users';

    /**
     * @return string
     */
    protected function getUri(): string
    {
        return static::COMPANY_USERS_URI;
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

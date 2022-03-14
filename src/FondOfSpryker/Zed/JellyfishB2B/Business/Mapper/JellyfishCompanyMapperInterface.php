<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\JellyfishCompanyTransfer;

interface JellyfishCompanyMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyTransfer
     */
    public function fromCompany(CompanyTransfer $companyTransfer): JellyfishCompanyTransfer;
}

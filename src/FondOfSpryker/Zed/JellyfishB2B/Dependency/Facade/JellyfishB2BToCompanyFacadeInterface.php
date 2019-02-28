<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade;

use Generated\Shared\Transfer\CompanyTransfer;

interface JellyfishB2BToCompanyFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompanyById(CompanyTransfer $companyTransfer): CompanyTransfer;
}

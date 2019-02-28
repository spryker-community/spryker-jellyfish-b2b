<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyUnitAddressCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return bool
     */
    public function isDefaultBilling(CompanyUnitAddressTransfer $companyUnitAddressTransfer): bool;
}

<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Checker;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyUnitAddressChecker implements CompanyUnitAddressCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return bool
     */
    public function isDefaultBilling(CompanyUnitAddressTransfer $companyUnitAddressTransfer): bool
    {
        $companyBusinessUnitCollectionTransfer = $companyUnitAddressTransfer->getCompanyBusinessUnits();

        if (
            $companyBusinessUnitCollectionTransfer === null
            || $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->count() !== 1
        ) {
            return false;
        }

        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer */
        $companyBusinessUnitTransfer = $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()
            ->offsetGet(0);

        return $companyBusinessUnitTransfer->getDefaultBillingAddress() === $companyUnitAddressTransfer->getIdCompanyUnitAddress();
    }
}

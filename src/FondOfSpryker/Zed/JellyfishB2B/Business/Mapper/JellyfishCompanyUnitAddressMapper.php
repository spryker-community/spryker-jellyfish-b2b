<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Mapper;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer;

class JellyfishCompanyUnitAddressMapper implements JellyfishCompanyUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer
     */
    public function fromCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): JellyfishCompanyUnitAddressTransfer {
        $jellyfishCompanyUnitAddressTransfer = (new JellyfishCompanyUnitAddressTransfer())
            ->fromArray($companyUnitAddressTransfer->toArray(), true);

        $jellyfishCompanyUnitAddressTransfer->setId($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->setCountry($companyUnitAddressTransfer->getIso2Code())
            ->setCompanyBusinessUnits($companyUnitAddressTransfer->getCompanyBusinessUnits());

        return $jellyfishCompanyUnitAddressTransfer;
    }
}

<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper;

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
        $jellyfishCompanyUnitAddressTransfer = new JellyfishCompanyUnitAddressTransfer();

        $jellyfishCompanyUnitAddressTransfer->setId($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->setUuid($companyUnitAddressTransfer->getUuid())
            ->setExternalReference($companyUnitAddressTransfer->getExternalReference())
            ->setName1($companyUnitAddressTransfer->getName1())
            ->setName2($companyUnitAddressTransfer->getName2())
            ->setAddress1($companyUnitAddressTransfer->getAddress1())
            ->setAddress2($companyUnitAddressTransfer->getAddress2())
            ->setAddress3($companyUnitAddressTransfer->getAddress3())
            ->setCity($companyUnitAddressTransfer->getCity())
            ->setZipCode($companyUnitAddressTransfer->getZipCode())
            ->setCountry($companyUnitAddressTransfer->getIso2Code())
            ->setPhone($companyUnitAddressTransfer->getPhone())
            ->setFax($companyUnitAddressTransfer->getFax())
            ->setGln($companyUnitAddressTransfer->getGln())
            ->setIsDeleted($companyUnitAddressTransfer->getIsDeleted())
            ->setIsDefault($companyUnitAddressTransfer->getIsDefault())
            ->setCompanyBusinessUnits($companyUnitAddressTransfer->getCompanyBusinessUnits());

        return $jellyfishCompanyUnitAddressTransfer;
    }
}

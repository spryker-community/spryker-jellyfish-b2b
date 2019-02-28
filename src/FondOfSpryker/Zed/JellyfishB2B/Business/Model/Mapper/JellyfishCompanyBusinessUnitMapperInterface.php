<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;

interface JellyfishCompanyBusinessUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompany(CompanyTransfer $companyTransfer): JellyfishCompanyBusinessUnitTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompanyBusinessUnit(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): JellyfishCompanyBusinessUnitTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): JellyfishCompanyBusinessUnitTransfer;
}

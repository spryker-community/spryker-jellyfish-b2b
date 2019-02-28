<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface;

class JellyfishB2BToCompanyUnitAddressFacadeBridge implements JellyfishB2BToCompanyUnitAddressFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    public function __construct(CompanyUnitAddressFacadeInterface $companyUnitAddressFacade)
    {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        return $this->companyUnitAddressFacade->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {
        return $this->companyUnitAddressFacade->getCompanyUnitAddressCollection($criteriaFilterTransfer);
    }
}

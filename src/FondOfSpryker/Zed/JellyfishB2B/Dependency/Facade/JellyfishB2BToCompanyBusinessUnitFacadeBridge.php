<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;

class JellyfishB2BToCompanyBusinessUnitFacadeBridge implements JellyfishB2BToCompanyBusinessUnitFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(CompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
        $this->companyBusinessFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param int $companyId
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    public function findDefaultBusinessUnitByCompanyId(int $companyId): ?CompanyBusinessUnitTransfer
    {
        return $this->companyBusinessFacade->findDefaultBusinessUnitByCompanyId($companyId);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        return $this->companyBusinessFacade->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
    }
}

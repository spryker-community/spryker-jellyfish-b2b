<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander;

use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserReferenceFacadeInterface;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\JellyfishOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class JellyfishOrderExpander implements JellyfishOrderExpanderInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserReferenceFacadeInterface
     */
    protected $companyUserReferenceFacade;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserReferenceFacadeInterface $companyUserReferenceFacade
     */
    public function __construct(JellyfishB2BToCompanyUserReferenceFacadeInterface $companyUserReferenceFacade)
    {
        $this->companyUserReferenceFacade = $companyUserReferenceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $spySalesOrder
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderTransfer
     */
    public function expand(JellyfishOrderTransfer $jellyfishOrderTransfer, SpySalesOrder $spySalesOrder): JellyfishOrderTransfer
    {
        $companyUserReference = $spySalesOrder->getCompanyUserReference();

        if ($companyUserReference === null) {
            return $jellyfishOrderTransfer;
        }

        $jellyfishOrderTransfer->setCompanyUserReference($companyUserReference);

        $companyUserTransfer = $this->findCompanyUserByCompanyUserReference($companyUserReference);

        if ($companyUserTransfer === null) {
            return $jellyfishOrderTransfer;
        }

        $jellyfishOrderTransfer = $this->expandWithCompanyFields($jellyfishOrderTransfer, $companyUserTransfer);
        $jellyfishOrderTransfer = $this->expandWithCompanyBusinessUnitFields(
            $jellyfishOrderTransfer,
            $companyUserTransfer,
        );

        return $jellyfishOrderTransfer;
    }

    /**
     * @param string $companyUserReference
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findCompanyUserByCompanyUserReference(string $companyUserReference): ?CompanyUserTransfer
    {
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setCompanyUserReference($companyUserReference);

        $companyUserResponseTransfer = $this->companyUserReferenceFacade
            ->findCompanyUserByCompanyUserReference($companyUserTransfer);

        if (!$companyUserResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $companyUserResponseTransfer->getCompanyUser();
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderTransfer
     */
    protected function expandWithCompanyFields(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): JellyfishOrderTransfer {
        $companyTransfer = $companyUserTransfer->getCompany();

        if ($companyTransfer === null && $companyUserTransfer->getCompanyBusinessUnit() !== null) {
            $companyTransfer = $companyUserTransfer->getCompanyBusinessUnit()->getCompany();
        }

        if ($companyTransfer === null) {
            return $jellyfishOrderTransfer;
        }

        return $jellyfishOrderTransfer->setCompanyUuid($companyTransfer->getUuid())
            ->setCompanyId($companyTransfer->getIdCompany());
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderTransfer
     */
    protected function expandWithCompanyBusinessUnitFields(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): JellyfishOrderTransfer {
        $companyBusinessUnitTransfer = $companyUserTransfer->getCompanyBusinessUnit();

        if ($companyBusinessUnitTransfer === null) {
            return $jellyfishOrderTransfer;
        }

        return $jellyfishOrderTransfer->setCompanyBusinessUnitUuid($companyBusinessUnitTransfer->getUuid())
            ->setCompanyBusinessUnitId($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());
    }
}

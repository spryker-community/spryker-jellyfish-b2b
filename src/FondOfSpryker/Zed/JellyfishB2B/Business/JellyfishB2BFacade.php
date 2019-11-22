<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business;

use Generated\Shared\Transfer\JellyfishOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BBusinessFactory getFactory()
 */
class JellyfishB2BFacade extends AbstractFacade implements JellyfishB2BFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $spySalesOrder
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderTransfer
     */
    public function expandJellyfishOrder(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        SpySalesOrder $spySalesOrder
    ): JellyfishOrderTransfer {
        return $this->getFactory()->createJellyfishOrderExpander()->expand($jellyfishOrderTransfer, $spySalesOrder);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyBulk(array $transfers): void
    {
        $this->getFactory()->createCompanyExporter()->exportBulk($transfers);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyUserBulk(array $transfers): void
    {
        $this->getFactory()->createCompanyUserExporter()->exportBulk($transfers);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyBusinessUnitBulk(array $transfers): void
    {
        $this->getFactory()->createCompanyBusinessUnitExporter()->exportBulk($transfers);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyUnitAddressBulk(array $transfers): void
    {
        $this->getFactory()->createCompanyUnitAddressExporter()->exportBulk($transfers);
    }
}

<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishExtension;

use FondOfSpryker\Zed\JellyfishExtension\Dependency\Plugin\JellyfishOrderExpanderPostMapPluginInterface;
use Generated\Shared\Transfer\JellyfishOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 */
class CompanyJellyfishOrderExpanderPostMapPlugin extends AbstractPlugin implements JellyfishOrderExpanderPostMapPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrder
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderAddressTransfer
     */
    public function expand(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        SpySalesOrder $spySalesOrder
    ): JellyfishOrderTransfer {
        return $this->getFacade()->expandJellyfishOrder($jellyfishOrderTransfer, $spySalesOrder);
    }
}

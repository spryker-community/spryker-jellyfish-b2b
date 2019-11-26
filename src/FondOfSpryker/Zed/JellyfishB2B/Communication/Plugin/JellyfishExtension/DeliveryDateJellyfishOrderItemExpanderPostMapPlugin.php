<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishExtension;

use FondOfSpryker\Zed\JellyfishExtension\Dependency\Plugin\JellyfishOrderItemExpanderPostMapPluginInterface;
use Generated\Shared\Transfer\JellyfishOrderItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 */
class DeliveryDateJellyfishOrderItemExpanderPostMapPlugin extends AbstractPlugin implements JellyfishOrderItemExpanderPostMapPluginInterface
{
    /**
     * Specification:
     *  - Allows to manipulate JellyfishOrderItemTransfer object after mapping.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\JellyfishOrderItemTransfer $jellyfishOrderItemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @throws
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderItemTransfer
     */
    public function expand(
        JellyfishOrderItemTransfer $jellyfishOrderItemTransfer,
        SpySalesOrderItem $salesOrderItem
    ): JellyfishOrderItemTransfer {
        $deliveryDate = $salesOrderItem->getConcreteDeliveryDate();

        $jellyfishOrderItemTransfer->setDeliveryDate($deliveryDate);

        return $jellyfishOrderItemTransfer;
    }
}

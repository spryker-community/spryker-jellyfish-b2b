<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander;

use Generated\Shared\Transfer\JellyfishOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface OrderCustomReferenceJellyfishOrderExpanderInterface
{
    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\SpySalesOrder $spySalesOrder
     *
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\JellyfishOrderTransfer
     */
    public function expand(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        SpySalesOrder $spySalesOrder
    ): JellyfishOrderTransfer;
}

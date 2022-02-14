<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander;

use Generated\Shared\Transfer\JellyfishOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class OrderCommentJellyfishOrderExpander implements OrderCommentJellyfishOrderExpanderInterface
{
 /**
  * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
  * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $spySalesOrder
  *
  * @return \Generated\Shared\Transfer\JellyfishOrderTransfer
  */
    public function expand(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        SpySalesOrder $spySalesOrder
    ): JellyfishOrderTransfer {
        if ($spySalesOrder->getCartNote() === null) {
            return $jellyfishOrderTransfer;
        }

        $jellyfishOrderTransfer->setComment($spySalesOrder->getCartNote());

        return $jellyfishOrderTransfer;
    }
}

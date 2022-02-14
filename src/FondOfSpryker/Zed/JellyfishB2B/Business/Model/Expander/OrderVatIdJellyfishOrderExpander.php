<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander;

use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\JellyfishOrderTransfer;
use Orm\Zed\Sales\Persistence\Base\SpySalesOrder;

class OrderVatIdJellyfishOrderExpander implements OrderVatIdJellyfishOrderExpanderInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    public function __construct(JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade)
    {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishOrderTransfer $jellyfishOrderTransfer
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrder $spySalesOrder
     *
     * @return \Generated\Shared\Transfer\JellyfishOrderTransfer
     */
    public function expand(
        JellyfishOrderTransfer $jellyfishOrderTransfer,
        SpySalesOrder $spySalesOrder
    ): JellyfishOrderTransfer {
        $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())
            ->setIdCompanyUnitAddress($spySalesOrder->getShippingAddress()->getFkResourceCompanyUnitAddress());
        $companyUnitAddressTransfer = $this->companyUnitAddressFacade
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        return $jellyfishOrderTransfer->setVatId($companyUnitAddressTransfer->getVatId());
    }
}

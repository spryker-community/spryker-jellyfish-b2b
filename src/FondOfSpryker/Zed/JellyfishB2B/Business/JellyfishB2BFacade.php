<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BBusinessFactory getFactory()
 */
class JellyfishB2BFacade extends AbstractFacade implements JellyfishB2BFacadeInterface
{
    /**
     * @param array<\Spryker\Shared\Kernel\Transfer\TransferInterface> $transfers
     *
     * @return void
     */
    public function exportCompanyBulk(array $transfers): void
    {
        $this->getFactory()->createCompanyExporter()->exportBulk($transfers);
    }

    /**
     * @param array<\Spryker\Shared\Kernel\Transfer\TransferInterface> $transfers
     *
     * @return void
     */
    public function exportCompanyUnitAddressBulk(array $transfers): void
    {
        $this->getFactory()->createCompanyUnitAddressExporter()->exportBulk($transfers);
    }
}

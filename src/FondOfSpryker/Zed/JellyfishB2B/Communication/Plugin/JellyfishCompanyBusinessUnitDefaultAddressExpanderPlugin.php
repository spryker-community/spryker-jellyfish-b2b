<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin;

use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 */
class JellyfishCompanyBusinessUnitDefaultAddressExpanderPlugin extends AbstractPlugin implements JellyfishCompanyBusinessUnitExpanderPluginInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(
        JellyfishB2BToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function expand(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        if ($jellyfishCompanyBusinessUnitTransfer->getId() === null) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitById(
            (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($jellyfishCompanyBusinessUnitTransfer->getId()),
        );

        foreach ($jellyfishCompanyBusinessUnitTransfer->getAddresses() as $jellyfishCompanyUnitAddressTransfer) {
            if (
                $this->isDefaultShippingAddress(
                    $companyBusinessUnitTransfer,
                    $jellyfishCompanyUnitAddressTransfer,
                ) === false
            ) {
                $jellyfishCompanyUnitAddressTransfer->setIsDefault(false);

                continue;
            }

            $jellyfishCompanyUnitAddressTransfer->setIsDefault(true);
        }

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer $jellyfishCompanyUnitAddressTransfer
     *
     * @return bool
     */
    protected function isDefaultShippingAddress(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        JellyfishCompanyUnitAddressTransfer $jellyfishCompanyUnitAddressTransfer
    ): bool {
        return $companyBusinessUnitTransfer->getDefaultShippingAddress() === $jellyfishCompanyUnitAddressTransfer->getId();
    }
}

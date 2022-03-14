<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin;

use FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyUnitAddressMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 */
class JellyfishCompanyBusinessUnitBillingAddressExpanderPlugin extends AbstractPlugin implements JellyfishCompanyBusinessUnitExpanderPluginInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyUnitAddressMapperInterface
     */
    protected $jellyfishCompanyUnitAddressMapper;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyUnitAddressMapperInterface $jellyfishCompanyUnitAddressMapper
     */
    public function __construct(
        JellyfishB2BToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade,
        JellyfishCompanyUnitAddressMapperInterface $jellyfishCompanyUnitAddressMapper
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
        $this->jellyfishCompanyUnitAddressMapper = $jellyfishCompanyUnitAddressMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function expand(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        if (
            $jellyfishCompanyBusinessUnitTransfer->getId() === null ||
            $jellyfishCompanyBusinessUnitTransfer->getBillingAddress() !== null
        ) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitById(
            (new CompanyBusinessUnitTransfer())
                ->setIdCompanyBusinessUnit($jellyfishCompanyBusinessUnitTransfer->getId()),
        );

        if ($companyBusinessUnitTransfer->getDefaultBillingAddress() === null) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())
            ->setIdCompanyUnitAddress($companyBusinessUnitTransfer->getDefaultBillingAddress());

        $companyUnitAddressTransfer = $this->companyUnitAddressFacade->getCompanyUnitAddressById($companyUnitAddressTransfer);

        $jellyfishCompanyUnitAddressTransfer = $this->jellyfishCompanyUnitAddressMapper
            ->fromCompanyUnitAddress($companyUnitAddressTransfer);

        $jellyfishCompanyBusinessUnitTransfer->setBillingAddress($jellyfishCompanyUnitAddressTransfer);

        return $jellyfishCompanyBusinessUnitTransfer;
    }
}

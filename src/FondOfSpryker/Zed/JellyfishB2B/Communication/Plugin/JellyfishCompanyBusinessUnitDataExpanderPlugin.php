<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin;

use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Class JellyfishCompanyBusinessUnitDataExpanderPlugin
 *
 * @package FondOfSpryker\Zed\Jellyfish\Dependency\Plugin
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 */
class JellyfishCompanyBusinessUnitDataExpanderPlugin extends AbstractPlugin implements JellyfishCompanyBusinessUnitExpanderPluginInterface
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
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected $jellyfishCompanyBusinessUnitMapper;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper
     */
    public function __construct(
        JellyfishB2BToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade,
        JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
        $this->jellyfishCompanyBusinessUnitMapper = $jellyfishCompanyBusinessUnitMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function expand(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        if ($jellyfishCompanyBusinessUnitTransfer->getId()) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        if ($jellyfishCompanyBusinessUnitTransfer->getCompany() !== null) {
            return $this->expandByCompany($jellyfishCompanyBusinessUnitTransfer);
        }

        if ($jellyfishCompanyBusinessUnitTransfer->getBillingAddress() !== null) {
            return $this->expandByBillingAddress($jellyfishCompanyBusinessUnitTransfer);
        }

        if ($jellyfishCompanyBusinessUnitTransfer->getAddresses() !== null && $jellyfishCompanyBusinessUnitTransfer->getAddresses()->count()) {
            return $this->expandByAddress($jellyfishCompanyBusinessUnitTransfer);
        }

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expandByAddress(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $jellyfishCompanyUnitAddressTransfer = $jellyfishCompanyBusinessUnitTransfer->getAddresses()->offsetGet(0);

        if ($jellyfishCompanyUnitAddressTransfer->getCompanyBusinessUnits() !== null) {
            return $this->expandByCompanyUnitAddressTransfer($jellyfishCompanyBusinessUnitTransfer, $jellyfishCompanyUnitAddressTransfer);
        }

        $idCompanyUnitAddress = $jellyfishCompanyUnitAddressTransfer->getId();

        return $this->expandByCompanyUnitAddressId($jellyfishCompanyBusinessUnitTransfer, $idCompanyUnitAddress);
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expandByBillingAddress(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $idCompanyUnitAddress = $jellyfishCompanyBusinessUnitTransfer->getBillingAddress()->getId();

        return $this->expandByCompanyUnitAddressId($jellyfishCompanyBusinessUnitTransfer, $idCompanyUnitAddress);
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expandByCompany(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade->findDefaultBusinessUnitByCompanyId(
            $jellyfishCompanyBusinessUnitTransfer->getCompany()->getId(),
        );

        if ($companyBusinessUnitTransfer === null) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        return $this->expandByCompanyBusinessUnit($jellyfishCompanyBusinessUnitTransfer, $companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expandByCompanyBusinessUnit(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $temp = $this->jellyfishCompanyBusinessUnitMapper->fromCompanyBusinessUnit($companyBusinessUnitTransfer);

        $jellyfishCompanyBusinessUnitTransfer->fromArray($temp->modifiedToArray(), true);

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expandByCompanyUnitAddressId(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer,
        int $idCompanyUnitAddress
    ): JellyfishCompanyBusinessUnitTransfer {
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddressTransfer = $this->companyUnitAddressFacade->getCompanyUnitAddressById($companyUnitAddressTransfer);

        $companyBusinessUnitCollectionTransfer = $companyUnitAddressTransfer->getCompanyBusinessUnits();

        if ($companyBusinessUnitCollectionTransfer === null || !$companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->offsetExists(0)) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        $companyBusinessUnitTransfer = $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()
            ->offsetGet(0);

        return $this->expandByCompanyBusinessUnit($jellyfishCompanyBusinessUnitTransfer, $companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer $jellyfishCompanyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expandByCompanyUnitAddressTransfer(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer,
        JellyfishCompanyUnitAddressTransfer $jellyfishCompanyUnitAddressTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $jellyfishCompanyBusinessUnitCollectionTransfer = $jellyfishCompanyUnitAddressTransfer->getCompanyBusinessUnits();

        if ($jellyfishCompanyBusinessUnitCollectionTransfer === null || !$jellyfishCompanyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->offsetExists(0)) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        $companyBusinessUnitTransfer = $jellyfishCompanyBusinessUnitCollectionTransfer
            ->getCompanyBusinessUnits()
            ->offsetGet(0);

        return $this->expandByCompanyBusinessUnit($jellyfishCompanyBusinessUnitTransfer, $companyBusinessUnitTransfer);
    }
}

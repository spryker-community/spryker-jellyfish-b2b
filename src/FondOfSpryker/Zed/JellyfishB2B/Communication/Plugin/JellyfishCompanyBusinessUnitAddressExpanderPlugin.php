<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin;

use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 */
class JellyfishCompanyBusinessUnitAddressExpanderPlugin extends AbstractPlugin implements JellyfishCompanyBusinessUnitExpanderPluginInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface
     */
    protected $jellyfishCompanyUnitAddressMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface
     */
    protected $companyUnitAddressChecker;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface $jellyfishCompanyUnitAddressMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface $companyUnitAddressChecker
     */
    public function __construct(
        JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade,
        JellyfishCompanyUnitAddressMapperInterface $jellyfishCompanyUnitAddressMapper,
        CompanyUnitAddressCheckerInterface $companyUnitAddressChecker
    ) {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
        $this->jellyfishCompanyUnitAddressMapper = $jellyfishCompanyUnitAddressMapper;
        $this->companyUnitAddressChecker = $companyUnitAddressChecker;
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

        $companyUnitAddressCriteriaFilterTransfer = new CompanyUnitAddressCriteriaFilterTransfer();

        $companyUnitAddressCriteriaFilterTransfer->setIdCompanyBusinessUnit($jellyfishCompanyBusinessUnitTransfer->getId());

        $companyUnitAddressCollectionTransfer = $this->companyUnitAddressFacade
            ->getCompanyUnitAddressCollection($companyUnitAddressCriteriaFilterTransfer);

        if ($companyUnitAddressCollectionTransfer === null) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $jellyfishCompanyUnitAddressTransfer = $this->jellyfishCompanyUnitAddressMapper
                ->fromCompanyUnitAddress($companyUnitAddressTransfer);

            if (!$this->companyUnitAddressChecker->isDefaultBilling($companyUnitAddressTransfer)
                && !$this->hasAddress($jellyfishCompanyBusinessUnitTransfer, $jellyfishCompanyUnitAddressTransfer)
            ) {
                $jellyfishCompanyBusinessUnitTransfer->addAddress($jellyfishCompanyUnitAddressTransfer);
                continue;
            }
        }

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\JellyfishCompanyUnitAddressTransfer $searchedJellyfishCompanyUnitAddressTransfer
     *
     * @return bool
     */
    protected function hasAddress(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer,
        JellyfishCompanyUnitAddressTransfer $searchedJellyfishCompanyUnitAddressTransfer
    ): bool {

        foreach ($jellyfishCompanyBusinessUnitTransfer->getAddresses() as $jellyfishCompanyUnitAddressTransfer) {
            if ($jellyfishCompanyUnitAddressTransfer->getId() === $searchedJellyfishCompanyUnitAddressTransfer->getId()) {
                return true;
            }
        }

        return false;
    }
}

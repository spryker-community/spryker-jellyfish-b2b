<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin;

use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
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

            if ($jellyfishCompanyBusinessUnitTransfer->getBillingAddress() === null
                && !$this->companyUnitAddressChecker->isDefaultBilling($companyUnitAddressTransfer)
            ) {
                $jellyfishCompanyBusinessUnitTransfer->addAddress($jellyfishCompanyUnitAddressTransfer);
                continue;
            }

            $jellyfishCompanyBusinessUnitTransfer->setBillingAddress($jellyfishCompanyUnitAddressTransfer);
        }

        return $jellyfishCompanyBusinessUnitTransfer;
    }
}

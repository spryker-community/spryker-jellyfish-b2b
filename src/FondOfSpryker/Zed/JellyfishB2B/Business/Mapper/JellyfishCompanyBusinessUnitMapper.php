<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Mapper;

use FondOfSpryker\Zed\JellyfishB2B\Business\Checker\CompanyUnitAddressCheckerInterface;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;

class JellyfishCompanyBusinessUnitMapper implements JellyfishCompanyBusinessUnitMapperInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyMapperInterface
     */
    protected $jellyfishCompanyMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyUnitAddressMapperInterface
     */
    protected $jellyfishCompanyUnitAddressMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Checker\CompanyUnitAddressCheckerInterface
     */
    protected $companyUnitAddressChecker;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyMapperInterface $jellyfishCompanyMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyUnitAddressMapperInterface $jellyfishCompanyUnitAddressMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Checker\CompanyUnitAddressCheckerInterface $companyUnitAddressChecker
     */
    public function __construct(
        JellyfishCompanyMapperInterface $jellyfishCompanyMapper,
        JellyfishCompanyUnitAddressMapperInterface $jellyfishCompanyUnitAddressMapper,
        CompanyUnitAddressCheckerInterface $companyUnitAddressChecker
    ) {
        $this->jellyfishCompanyMapper = $jellyfishCompanyMapper;
        $this->jellyfishCompanyUnitAddressMapper = $jellyfishCompanyUnitAddressMapper;
        $this->companyUnitAddressChecker = $companyUnitAddressChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompany(CompanyTransfer $companyTransfer): JellyfishCompanyBusinessUnitTransfer
    {
        $jellyfishCompany = $this->jellyfishCompanyMapper->fromCompany($companyTransfer);

        return (new JellyfishCompanyBusinessUnitTransfer())
            ->setCompany($jellyfishCompany);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $jellyfishCompanyBusinessUnitTransfer = (new JellyfishCompanyBusinessUnitTransfer())
            ->fromArray($companyBusinessUnitTransfer->toArray(), true);

        return $jellyfishCompanyBusinessUnitTransfer->setId($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $jellyfishCompanyUnitAddressTransfer = $this->jellyfishCompanyUnitAddressMapper->fromCompanyUnitAddress(
            $companyUnitAddressTransfer,
        );

        $jellyfishCompanyBusinessUnitTransfer = new JellyfishCompanyBusinessUnitTransfer();

        if ($this->companyUnitAddressChecker->isDefaultBilling($companyUnitAddressTransfer)) {
            return $jellyfishCompanyBusinessUnitTransfer->setBillingAddress($jellyfishCompanyUnitAddressTransfer);
        }

        return $jellyfishCompanyBusinessUnitTransfer->addAddress($jellyfishCompanyUnitAddressTransfer);
    }
}

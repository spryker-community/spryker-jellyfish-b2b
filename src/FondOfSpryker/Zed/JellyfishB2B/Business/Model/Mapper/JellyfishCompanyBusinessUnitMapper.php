<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper;

use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;

class JellyfishCompanyBusinessUnitMapper implements JellyfishCompanyBusinessUnitMapperInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface
     */
    protected $jellyfishCompanyMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface
     */
    protected $jellyfishCompanyUnitAddressMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface
     */
    protected $companyUnitAddressChecker;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface $jellyfishCompanyMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface $jellyfishCompanyUnitAddressMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface $companyUnitAddressChecker
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
        $jellyfishCompanyBusinessUnitTransfer = new JellyfishCompanyBusinessUnitTransfer();

        $jellyfishCompanyBusinessUnitTransfer->setCompany($jellyfishCompany);

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $jellyfishCompanyBusinessUnitTransfer = new JellyfishCompanyBusinessUnitTransfer();

        $jellyfishCompanyBusinessUnitTransfer->setId($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setUuid($companyBusinessUnitTransfer->getUuid())
            ->setExternalReference($companyBusinessUnitTransfer->getExternalReference())
            ->setName($companyBusinessUnitTransfer->getName())
            ->setEmail($companyBusinessUnitTransfer->getEmail());

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function fromCompanyUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        $jellyfishCompanyUnitAddressTransfer = $this->jellyfishCompanyUnitAddressMapper->fromCompanyUnitAddress($companyUnitAddressTransfer);

        $jellyfishCompanyBusinessUnitTransfer = new JellyfishCompanyBusinessUnitTransfer();

        if ($this->companyUnitAddressChecker->isDefaultBilling($companyUnitAddressTransfer)) {
            return $jellyfishCompanyBusinessUnitTransfer->setBillingAddress($jellyfishCompanyUnitAddressTransfer);
        }

        $jellyfishCompanyBusinessUnitTransfer->addAddress($jellyfishCompanyUnitAddressTransfer);

        return $jellyfishCompanyBusinessUnitTransfer;
    }
}

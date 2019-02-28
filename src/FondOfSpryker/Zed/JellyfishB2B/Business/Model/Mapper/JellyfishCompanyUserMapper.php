<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\JellyfishCompanyUserTransfer;

class JellyfishCompanyUserMapper implements JellyfishCompanyUserMapperInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected $jellyfishCompanyBusinessUnitMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCustomerMapperInterface
     */
    protected $jellyfishCustomerMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface
     */
    private $jellyfishCompanyMapper;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface $jellyfishCompanyMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCustomerMapperInterface $jellyfishCustomerMapper
     */
    public function __construct(
        JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper,
        JellyfishCompanyMapperInterface $jellyfishCompanyMapper,
        JellyfishCustomerMapperInterface $jellyfishCustomerMapper
    ) {
        $this->jellyfishCompanyBusinessUnitMapper = $jellyfishCompanyBusinessUnitMapper;
        $this->jellyfishCustomerMapper = $jellyfishCustomerMapper;
        $this->jellyfishCompanyMapper = $jellyfishCompanyMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyUserTransfer
     */
    public function fromCompanyUser(CompanyUserTransfer $companyUserTransfer): JellyfishCompanyUserTransfer
    {
        $jellyfishCompanyUser = new JellyfishCompanyUserTransfer();

        $jellyfishCompanyBusinessUnit = $this->jellyfishCompanyBusinessUnitMapper->fromCompanyBusinessUnit($companyUserTransfer->getCompanyBusinessUnit());
        $jellyfishCompanyBusinessUnit->setCompany($this->jellyfishCompanyMapper->fromCompany($companyUserTransfer->getCompany()));

        $jellyfishCompanyUser->setExternalReference($companyUserTransfer->getExternalReference());
        $jellyfishCompanyUser->setActive($companyUserTransfer->getIsActive());
        $jellyfishCompanyUser->setCustomer($this->jellyfishCustomerMapper->fromCustomer($companyUserTransfer->getCustomer()));
        $jellyfishCompanyUser->setCompanyBusinessUnit($jellyfishCompanyBusinessUnit);

        return $jellyfishCompanyUser;
    }
}

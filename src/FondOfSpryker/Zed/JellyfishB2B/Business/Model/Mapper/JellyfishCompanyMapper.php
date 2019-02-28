<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\JellyfishCompanyTransfer;

class JellyfishCompanyMapper implements JellyfishCompanyMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyTransfer
     */
    public function fromCompany(CompanyTransfer $companyTransfer): JellyfishCompanyTransfer
    {
        $jellyfishCompany = new JellyfishCompanyTransfer();
        $jellyfishCompany->setId($companyTransfer->getIdCompany())
            ->setUuid($companyTransfer->getUuid())
            ->setExternalReference($companyTransfer->getExternalReference())
            ->setName($companyTransfer->getName());

        return $jellyfishCompany;
    }
}

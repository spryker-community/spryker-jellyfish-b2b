<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\JellyfishCompanyTransfer;
use Generated\Shared\Transfer\JellyfishPriceListTransfer;

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
            ->setName($companyTransfer->getName())
            ->setPriceList($this->mapCompanyToPriceList($companyTransfer))
            ->setDebtorNumber($companyTransfer->getDebtorNumber())
            ->setBlockedFor($companyTransfer->getBlockedFor())
            ->setStatus($companyTransfer->getStatus())
            ->setIsActive($companyTransfer->getIsActive());

        return $jellyfishCompany;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishPriceListTransfer|null
     */
    protected function mapCompanyToPriceList(CompanyTransfer $companyTransfer): ?JellyfishPriceListTransfer
    {
        $jellyfishPriceList = new JellyfishPriceListTransfer();

        $priceList = $companyTransfer->getPriceList();

        if ($priceList === null) {
            return null;
        }

        $jellyfishPriceList->setIdPriceList($priceList->getIdPriceList())
            ->setName($priceList->getName());

        return $jellyfishPriceList;
    }
}

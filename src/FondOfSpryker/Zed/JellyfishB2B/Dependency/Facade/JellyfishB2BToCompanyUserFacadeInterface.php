<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface JellyfishB2BToCompanyUserFacadeInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;
}

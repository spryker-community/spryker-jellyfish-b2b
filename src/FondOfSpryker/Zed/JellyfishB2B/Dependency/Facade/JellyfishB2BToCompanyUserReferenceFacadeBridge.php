<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade;

use FondOfSpryker\Zed\CompanyUserReference\Business\CompanyUserReferenceFacadeInterface;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

class JellyfishB2BToCompanyUserReferenceFacadeBridge implements JellyfishB2BToCompanyUserReferenceFacadeInterface
{
    /**
     * @var \FondOfSpryker\Zed\CompanyUsersRestApi\Business\CompanyUsersRestApiFacadeInterface
     */
    protected $companyUserReferenceFacade;

    /**
     * @param \FondOfSpryker\Zed\CompanyUserReference\Business\CompanyUserReferenceFacadeInterface $companyUserReferenceFacade
     */
    public function __construct(
        CompanyUserReferenceFacadeInterface $companyUserReferenceFacade
    ) {
        $this->companyUserReferenceFacade = $companyUserReferenceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function findCompanyUserByCompanyUserReference(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserResponseTransfer {
        return $this->companyUserReferenceFacade->findCompanyUserByCompanyUserReference($companyUserTransfer);
    }
}

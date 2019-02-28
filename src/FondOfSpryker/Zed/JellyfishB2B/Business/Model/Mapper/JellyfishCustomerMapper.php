<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\JellyfishCustomerTransfer;

class JellyfishCustomerMapper implements JellyfishCustomerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCustomerTransfer
     */
    public function fromCustomer(CustomerTransfer $customerTransfer): JellyfishCustomerTransfer
    {
        $jellyfishCustomerTransfer = new JellyfishCustomerTransfer();

        $jellyfishCustomerTransfer->setId($customerTransfer->getIdCustomer());
        $jellyfishCustomerTransfer->setUuid($customerTransfer->getCustomerReference());
        $jellyfishCustomerTransfer->setExternalReference($customerTransfer->getExternalReference());
        $jellyfishCustomerTransfer->setEmail($customerTransfer->getEmail());
        $jellyfishCustomerTransfer->setAcceptedTerms(true);
        $jellyfishCustomerTransfer->setFirstName($customerTransfer->getFirstName());
        $jellyfishCustomerTransfer->setLastName($customerTransfer->getLastName());
        $jellyfishCustomerTransfer->setPhone($customerTransfer->getPhone());

        return $jellyfishCustomerTransfer;
    }
}

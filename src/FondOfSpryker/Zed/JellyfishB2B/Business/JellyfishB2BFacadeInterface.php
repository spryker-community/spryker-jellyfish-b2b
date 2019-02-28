<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business;

interface JellyfishB2BFacadeInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyBulk(array $transfers): void;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyUserBulk(array $transfers): void;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyBusinessUnitBulk(array $transfers): void;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportCompanyUnitAddressBulk(array $transfers): void;
}

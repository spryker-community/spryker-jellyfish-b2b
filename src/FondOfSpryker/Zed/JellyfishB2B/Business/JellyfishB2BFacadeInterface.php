<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business;

interface JellyfishB2BFacadeInterface
{
    /**
     * @param array<\Spryker\Shared\Kernel\Transfer\TransferInterface> $transfers
     *
     * @return void
     */
    public function exportCompanyBulk(array $transfers): void;

    /**
     * @param array<\Spryker\Shared\Kernel\Transfer\TransferInterface> $transfers
     *
     * @return void
     */
    public function exportCompanyUnitAddressBulk(array $transfers): void;
}

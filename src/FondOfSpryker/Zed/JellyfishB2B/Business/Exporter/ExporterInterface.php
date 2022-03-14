<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Exporter;

interface ExporterInterface
{
    /**
     * @param array<\Spryker\Shared\Kernel\Transfer\TransferInterface> $transfers
     *
     * @return void
     */
    public function exportBulk(array $transfers): void;
}

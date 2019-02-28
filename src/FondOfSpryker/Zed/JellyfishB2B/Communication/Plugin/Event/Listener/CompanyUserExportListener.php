<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Listener;

use FondOfSpryker\Zed\JellyfishB2B\Dependency\JellyfishB2BEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Log\LoggerTrait;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 */
class CompanyUserExportListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use LoggerTrait;

    /**
     * Specification
     *  - Listeners needs to implement this interface to execute the codes for more
     *  than one event at same time (Bulk Operation)
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        if ($eventName === JellyfishB2BEvents::ENTITY_SPY_COMPANY_USER_CREATE
            || $eventName === JellyfishB2BEvents::ENTITY_SPY_COMPANY_USER_UPDATE
        ) {
            $this->getFacade()->exportCompanyUserBulk($transfers);
        }
    }
}

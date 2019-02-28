<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Subscriber;

use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Listener\CompanyBusinessUnitExportListener;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Listener\CompanyExportListener;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Listener\CompanyUnitAddressExportListener;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Listener\CompanyUserExportListener;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\JellyfishB2BEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\Business\JellyfishB2BFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 */
class JellyfishB2BEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_UPDATE,
            new CompanyExportListener()
        );

        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_BUSINESS_UNIT_UPDATE,
            new CompanyBusinessUnitExportListener()
        );

        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_UNIT_ADDRESS_CREATE,
            new CompanyUnitAddressExportListener()
        );

        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_UNIT_ADDRESS_UPDATE,
            new CompanyUnitAddressExportListener()
        );

        // Company User
        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_USER_CREATE,
            new CompanyUserExportListener()
        );

        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_USER_UPDATE,
            new CompanyUserExportListener()
        );

        return $eventCollection;
    }
}

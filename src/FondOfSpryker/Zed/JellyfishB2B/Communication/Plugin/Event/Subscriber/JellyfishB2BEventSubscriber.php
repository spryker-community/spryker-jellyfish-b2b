<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Subscriber;

use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Listener\CompanyExportListener;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\Event\Listener\CompanyUnitAddressExportListener;
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
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_UPDATE,
            new CompanyExportListener(),
        );

        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::COMPANY_UNIT_ADDRESS_AFTER_DELETE,
            new CompanyUnitAddressExportListener(),
        );

        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_UNIT_ADDRESS_CREATE,
            new CompanyUnitAddressExportListener(),
        );

        $eventCollection->addListenerQueued(
            JellyfishB2BEvents::ENTITY_SPY_COMPANY_UNIT_ADDRESS_UPDATE,
            new CompanyUnitAddressExportListener(),
        );

        return $eventCollection;
    }
}

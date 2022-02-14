<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency;

interface JellyfishB2BEvents
{
    /**
     * @var string
     */
    public const ENTITY_SPY_COMPANY_UPDATE = 'Entity.spy_company.update';

    /**
     * @var string
     */
    public const ENTITY_SPY_COMPANY_BUSINESS_UNIT_UPDATE = 'Entity.spy_company_business_unit.update';

    /**
     * @var string
     */
    public const ENTITY_SPY_COMPANY_UNIT_ADDRESS_CREATE = 'Entity.spy_company_unit_address.create';

    /**
     * @var string
     */
    public const ENTITY_SPY_COMPANY_UNIT_ADDRESS_UPDATE = 'Entity.spy_company_unit_address.update';

    /**
     * @var string
     */
    public const ENTITY_SPY_COMPANY_USER_CREATE = 'Entity.spy_company_user.create';

    /**
     * @var string
     */
    public const ENTITY_SPY_COMPANY_USER_UPDATE = 'Entity.spy_company_user.update';

    /**
     * @var string
     */
    public const COMPANY_UNIT_ADDRESS_AFTER_DELETE = 'company_unit_address.after.delete';

    /**
     * @var string
     */
    public const COMPANY_USER_AFTER_DELETE = 'company_user.after.delete';
}

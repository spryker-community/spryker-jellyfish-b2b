<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency;

interface JellyfishB2BEvents
{
    public const ENTITY_SPY_COMPANY_UPDATE = 'Entity.spy_company.update';

    public const ENTITY_SPY_COMPANY_BUSINESS_UNIT_UPDATE = 'Entity.spy_company_business_unit.update';

    public const ENTITY_SPY_COMPANY_UNIT_ADDRESS_CREATE = 'Entity.spy_company_unit_address.create';
    public const ENTITY_SPY_COMPANY_UNIT_ADDRESS_UPDATE = 'Entity.spy_company_unit_address.update';

    public const ENTITY_SPY_COMPANY_USER_CREATE = 'Entity.spy_company_user.create';
    public const ENTITY_SPY_COMPANY_USER_UPDATE = 'Entity.spy_company_user.update';
}

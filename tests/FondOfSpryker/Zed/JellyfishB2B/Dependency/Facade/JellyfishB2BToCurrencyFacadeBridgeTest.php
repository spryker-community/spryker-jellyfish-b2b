<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;

class JellyfishB2BToCurrencyFacadeBridgeTest extends Unit
{
    /**
     * @var \Spryker\Zed\Currency\Business\CurrencyFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $facadeMock;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $currencyTransferMock;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCurrencyFacadeBridge
     */
    protected $bridge;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->facadeMock = $this->getMockBuilder(CurrencyFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyTransferMock = $this->getMockBuilder(CurrencyTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bridge = new JellyfishB2BToCurrencyFacadeBridge($this->facadeMock);
    }

    /**
     * @return void
     */
    public function testGetByIdCurrency(): void
    {
        $idCurrency = 1;

        $this->facadeMock->expects(static::atLeastOnce())
            ->method('getByIdCurrency')
            ->with($idCurrency)
            ->willReturn($this->currencyTransferMock);

        static::assertEquals(
            $this->currencyTransferMock,
            $this->bridge->getByIdCurrency($idCurrency)
        );
    }
}

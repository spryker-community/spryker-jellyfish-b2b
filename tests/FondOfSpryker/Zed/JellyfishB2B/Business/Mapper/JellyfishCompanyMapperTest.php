<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Mapper;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCurrencyFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToLocaleFacadeInterface;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PriceListTransfer;

class JellyfishCompanyMapperTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToLocaleFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $localFacadeMock;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCurrencyFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $currencyFacadeMock;

    /**
     * @var \Generated\Shared\Transfer\CompanyTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $companyTransferMock;

    /**
     * @var \Generated\Shared\Transfer\PriceListTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $priceListTransferMock;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $localeTransferMock;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $currencyTransferMock;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Mapper\JellyfishCompanyMapper
     */
    protected $jellyfishCompanyMapper;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->localFacadeMock = $this->getMockBuilder(JellyfishB2BToLocaleFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyFacadeMock = $this->getMockBuilder(JellyfishB2BToCurrencyFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->companyTransferMock = $this->getMockBuilder(CompanyTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListTransferMock = $this->getMockBuilder(PriceListTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeTransferMock = $this->getMockBuilder(LocaleTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyTransferMock = $this->getMockBuilder(CurrencyTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->jellyfishCompanyMapper = new JellyfishCompanyMapper(
            $this->localFacadeMock,
            $this->currencyFacadeMock
        );
    }

    /**
     * @return void
     */
    public function testFromCompany(): void
    {
        $idCompany = 1;
        $fkLocale = 2;
        $fkCurrency = 3;

        $localeName = 'de_DE';
        $currencyCode = 'EUR';

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn([]);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getIdCompany')
            ->willReturn($idCompany);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getPriceList')
            ->willReturn($this->priceListTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn([]);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('requireFkLocale')
            ->willReturn($this->companyTransferMock);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getFkLocale')
            ->willReturn($fkLocale);

        $this->localFacadeMock->expects(static::atLeastOnce())
            ->method('getLocaleById')
            ->with($fkLocale)
            ->willReturn($this->localeTransferMock);

        $this->localeTransferMock->expects(static::atLeastOnce())
            ->method('getLocaleName')
            ->willReturn($localeName);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('requireFkCurrency')
            ->willReturn($this->companyTransferMock);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getFkCurrency')
            ->willReturn($fkCurrency);

        $this->currencyFacadeMock->expects(static::atLeastOnce())
            ->method('getByIdCurrency')
            ->with($fkCurrency)
            ->willReturn($this->currencyTransferMock);

        $this->currencyTransferMock->expects(static::atLeastOnce())
            ->method('getCode')
            ->willReturn($currencyCode);

        $jellyfishCompanyTransfer = $this->jellyfishCompanyMapper->fromCompany($this->companyTransferMock);

        static::assertEquals($idCompany, $jellyfishCompanyTransfer->getId());
        static::assertNotEquals(null, $jellyfishCompanyTransfer->getPriceList());
        static::assertEquals($localeName, $jellyfishCompanyTransfer->getLocale());
        static::assertEquals($currencyCode, $jellyfishCompanyTransfer->getCurrency());
    }

    /**
     * @return void
     */
    public function testFromCompanyWithoutPriceList(): void
    {
        $idCompany = 1;
        $fkLocale = 2;
        $fkCurrency = 3;

        $localeName = 'de_DE';
        $currencyCode = 'EUR';

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn([]);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getIdCompany')
            ->willReturn($idCompany);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getPriceList')
            ->willReturn(null);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('requireFkLocale')
            ->willReturn($this->companyTransferMock);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getFkLocale')
            ->willReturn($fkLocale);

        $this->localFacadeMock->expects(static::atLeastOnce())
            ->method('getLocaleById')
            ->with($fkLocale)
            ->willReturn($this->localeTransferMock);

        $this->localeTransferMock->expects(static::atLeastOnce())
            ->method('getLocaleName')
            ->willReturn($localeName);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('requireFkCurrency')
            ->willReturn($this->companyTransferMock);

        $this->companyTransferMock->expects(static::atLeastOnce())
            ->method('getFkCurrency')
            ->willReturn($fkCurrency);

        $this->currencyFacadeMock->expects(static::atLeastOnce())
            ->method('getByIdCurrency')
            ->with($fkCurrency)
            ->willReturn($this->currencyTransferMock);

        $this->currencyTransferMock->expects(static::atLeastOnce())
            ->method('getCode')
            ->willReturn($currencyCode);

        $jellyfishCompanyTransfer = $this->jellyfishCompanyMapper->fromCompany($this->companyTransferMock);

        static::assertEquals($idCompany, $jellyfishCompanyTransfer->getId());
        static::assertEquals(null, $jellyfishCompanyTransfer->getPriceList());
        static::assertEquals($localeName, $jellyfishCompanyTransfer->getLocale());
        static::assertEquals($currencyCode, $jellyfishCompanyTransfer->getCurrency());
    }
}

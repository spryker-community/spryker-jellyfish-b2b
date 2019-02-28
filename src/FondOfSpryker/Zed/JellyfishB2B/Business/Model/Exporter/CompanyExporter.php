<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter;

use FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyFacadeInterface;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;

class CompanyExporter implements ExporterInterface
{
    use LoggerTrait;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected $jellyfishCompanyBusinessUnitMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface[]
     */
    protected $jellyfishCompanyBusinessUnitExpanderPlugins;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyFacadeInterface $companyFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface[] $jellyfishCompanyBusinessUnitExpanderPlugins
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter\AdapterInterface $adapter
     */
    public function __construct(
        JellyfishB2BToCompanyFacadeInterface $companyFacade,
        JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper,
        array $jellyfishCompanyBusinessUnitExpanderPlugins,
        AdapterInterface $adapter
    ) {
        $this->companyFacade = $companyFacade;
        $this->jellyfishCompanyBusinessUnitMapper = $jellyfishCompanyBusinessUnitMapper;
        $this->jellyfishCompanyBusinessUnitExpanderPlugins = $jellyfishCompanyBusinessUnitExpanderPlugins;
        $this->adapter = $adapter;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportBulk(array $transfers): void
    {
        foreach ($transfers as $transfer) {
            if (!$this->canExport($transfer)) {
                continue;
            }

            $this->exportById($transfer->getId());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function map(CompanyTransfer $companyTransfer): JellyfishCompanyBusinessUnitTransfer
    {
        return $this->jellyfishCompanyBusinessUnitMapper->fromCompany($companyTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expand(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        foreach ($this->jellyfishCompanyBusinessUnitExpanderPlugins as $jellyfishCompanyBusinessUnitExpanderPlugin) {
            $jellyfishCompanyBusinessUnitTransfer = $jellyfishCompanyBusinessUnitExpanderPlugin
                ->expand($jellyfishCompanyBusinessUnitTransfer);
        }

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return bool
     */
    protected function canExport(TransferInterface $transfer): bool
    {
        return $transfer instanceof EventEntityTransfer &&
            count($transfer->getModifiedColumns()) > 0 &&
            $transfer->getName() === 'spy_company';
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function exportById(int $id): void
    {
        $companyTransfer = new CompanyTransfer();
        $companyTransfer->setIdCompany($id);

        $companyTransfer = $this->companyFacade->getCompanyById($companyTransfer);

        if ($companyTransfer->getIdCompany() === null) {
            return;
        }

        $jellyfishCompanyBusinessUnitTransfer = $this->map($companyTransfer);
        $jellyfishCompanyBusinessUnitTransfer = $this->expand($jellyfishCompanyBusinessUnitTransfer);

        $this->adapter->sendRequest($jellyfishCompanyBusinessUnitTransfer);
    }
}

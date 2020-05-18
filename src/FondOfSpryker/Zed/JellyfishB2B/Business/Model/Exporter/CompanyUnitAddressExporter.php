<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;

class CompanyUnitAddressExporter implements ExporterInterface
{
    use LoggerTrait;

    public const EVENT_ENTITY_TRANSFER_NAME = 'spy_company_unit_address';

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    protected $companyUnitAddressFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface[]
     */
    protected $jellyfishCompanyBusinessUnitExpanderPlugins;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected $jellyfishCompanyBusinessUnitMapper;

    /**
     * @var \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2BExtension\Dependency\Plugin\EventEntityTransferExportValidatorPluginInterface[]
     */
    protected $validatorPlugins;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface[] $jellyfishCompanyBusinessUnitExpanderPlugins
     * @param \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface $adapter
     * @param \FondOfSpryker\Zed\JellyfishB2BExtension\Dependency\Plugin\EventEntityTransferExportValidatorPluginInterface[] $validatorPlugins
     */
    public function __construct(
        JellyfishB2BToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade,
        JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper,
        array $jellyfishCompanyBusinessUnitExpanderPlugins,
        AdapterInterface $adapter,
        array $validatorPlugins
    ) {
        $this->jellyfishCompanyBusinessUnitExpanderPlugins = $jellyfishCompanyBusinessUnitExpanderPlugins;
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
        $this->jellyfishCompanyBusinessUnitMapper = $jellyfishCompanyBusinessUnitMapper;
        $this->adapter = $adapter;
        $this->validatorPlugins = $validatorPlugins;
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

            /** @var \Generated\Shared\Transfer\EventEntityTransfer $transfer */
            $this->export($transfer);
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return bool
     */
    protected function canExport(TransferInterface $transfer): bool
    {
        return ($transfer instanceof EventEntityTransfer &&
            count($transfer->getModifiedColumns()) > 0 &&
            $transfer->getName() === self::EVENT_ENTITY_TRANSFER_NAME &&
            $this->validateExport($transfer)) ||
            ($transfer instanceof CompanyUnitAddressTransfer &&
                $this->validateExport(
                    $this->mapCompanyUnitAddressTransferToEventEntityTransfer($transfer)
                )
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function map(CompanyUnitAddressTransfer $companyUnitAddressTransfer): JellyfishCompanyBusinessUnitTransfer
    {
        return $this->jellyfishCompanyBusinessUnitMapper->fromCompanyUnitAddress($companyUnitAddressTransfer);
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
     * @return void
     */
    public function export(TransferInterface $transfer): void
    {
        $companyUnitAddressTransfer = $this->getCompanyUnitAddress($transfer);

        if ($companyUnitAddressTransfer === null || $companyUnitAddressTransfer->getIdCompanyUnitAddress() === null) {
            return;
        }

        $jellyfishCompanyBusinessUnitTransfer = $this->map($companyUnitAddressTransfer);
        $jellyfishCompanyBusinessUnitTransfer = $this->expand($jellyfishCompanyBusinessUnitTransfer);

        $this->adapter->sendRequest($jellyfishCompanyBusinessUnitTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    protected function getCompanyUnitAddress(TransferInterface $transfer): ?CompanyUnitAddressTransfer
    {
        if ($transfer instanceof CompanyUnitAddressTransfer) {
            return $transfer;
        }

        if (!($transfer instanceof EventEntityTransfer)) {
            return null;
        }

        $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())
            ->setIdCompanyUnitAddress($transfer->getId());

        return $this->companyUnitAddressFacade
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    protected function mapCompanyUnitAddressTransferToEventEntityTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): EventEntityTransfer {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setName(self::EVENT_ENTITY_TRANSFER_NAME)
            ->setForeignKeys(
                [
                    sprintf('%s.fk_company', self::EVENT_ENTITY_TRANSFER_NAME) =>
                        $companyUnitAddressTransfer->getFkCompany(),
                ]
            );

        return $eventEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $transfer
     *
     * @return bool
     */
    protected function validateExport(EventEntityTransfer $transfer): bool
    {
        foreach ($this->validatorPlugins as $validatorPlugin) {
            if ($validatorPlugin->validate($transfer) === false) {
                return false;
            }
        }

        return true;
    }
}

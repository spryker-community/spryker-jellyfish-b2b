<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserFacadeInterface;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;

class CompanyUserExporter implements ExporterInterface
{
    use LoggerTrait;

    protected const EVENT_ENTITY_TRANSFER_NAME = 'spy_company_user';
    protected const EVENT_ENTITY_TRANSFER_DATA_KEY_COMPANY_USER = 'company_user';

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserFacadeInterface $companyUserFacade
     */
    protected $companyUserFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapperInterface $jellyfishCompanyUserMapper
     */
    protected $jellyfishCompanyUserMapper;

    /**
     * @var \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2BExtension\Dependency\Plugin\CompanyUserExpanderPluginInterface[]
     */
    protected $companyUserExpanderPlugins;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2BExtension\Dependency\Plugin\EventEntityTransferExportValidatorPluginInterface[]
     */
    protected $validatorPlugins;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserFacadeInterface $companyUserFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapperInterface $jellyfishCompanyUserMapper
     * @param \FondOfSpryker\Zed\JellyfishB2BExtension\Dependency\Plugin\CompanyUserExpanderPluginInterface[] $companyUserExpanderPlugins
     * @param \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface $adapter
     * @param \FondOfSpryker\Zed\JellyfishB2BExtension\Dependency\Plugin\EventEntityTransferExportValidatorPluginInterface[] $validatorPlugins
     */
    public function __construct(
        JellyfishB2BToCompanyUserFacadeInterface $companyUserFacade,
        JellyfishCompanyUserMapperInterface $jellyfishCompanyUserMapper,
        array $companyUserExpanderPlugins,
        AdapterInterface $adapter,
        array $validatorPlugins
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->jellyfishCompanyUserMapper = $jellyfishCompanyUserMapper;
        $this->adapter = $adapter;
        $this->validatorPlugins = $validatorPlugins;
        $this->companyUserExpanderPlugins = $companyUserExpanderPlugins;
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
            $transfer->getName() === 'spy_company_user' &&
            $this->validateExport($transfer)) ||
            ($transfer instanceof  CompanyUserTransfer &&
                $this->validateExport(
                    $this->mapCompanyUserTransferToEventEntityTransfer($transfer)
                )
            );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function export(TransferInterface $transfer): void
    {
        $companyUserTransfer = $this->getCompanyUser($transfer);

        if ($companyUserTransfer === null || $companyUserTransfer->getIdCompanyUser() === null) {
            return;
        }

        $companyUserTransfer = $this->expandCompanyUserTransfer($companyUserTransfer);
        $jellyfishCompanyUserTransfer = $this->jellyfishCompanyUserMapper->fromCompanyUser($companyUserTransfer);

        $this->adapter->sendRequest($jellyfishCompanyUserTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function getCompanyUser(TransferInterface $transfer): ?CompanyUserTransfer
    {
        if ($transfer instanceof CompanyUserTransfer) {
            return $transfer;
        }

        if ($transfer instanceof EventEntityTransfer) {
            return $this->companyUserFacade->getCompanyUserById($transfer->getId());
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    protected function mapCompanyUserTransferToEventEntityTransfer(
        CompanyUserTransfer $companyUserTransfer
    ): EventEntityTransfer {
        $eventEntityTransfer = new EventEntityTransfer();
        $eventEntityTransfer->setName(self::EVENT_ENTITY_TRANSFER_NAME)
            ->setId($companyUserTransfer->getIdCompanyUser())
            ->setForeignKeys(
                [
                    sprintf('%s.fk_company', self::EVENT_ENTITY_TRANSFER_NAME) =>
                        $companyUserTransfer->getFkCompany(),
                ]
            )
        ->setTransferData(
            [
                self::EVENT_ENTITY_TRANSFER_DATA_KEY_COMPANY_USER => $companyUserTransfer,
            ]
        );

        return $eventEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function expandCompanyUserTransfer(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyUserTransfer {
        foreach ($this->companyUserExpanderPlugins as $companyUserExpanderPlugin) {
             $companyUserTransfer = $companyUserExpanderPlugin
                ->expand($companyUserTransfer);
        }

        return $companyUserTransfer;
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

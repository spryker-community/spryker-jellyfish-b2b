<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserFacadeInterface;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyTypeTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;

class CompanyUserExporter implements ExporterInterface
{
    use LoggerTrait;

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
     * @var array
     */
    protected $validatorPlugins;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserFacadeInterface $companyUserFacade
     * @param \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapperInterface $jellyfishCompanyUserMapper
     * @param \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface $adapter
     */
    public function __construct(
        JellyfishB2BToCompanyUserFacadeInterface $companyUserFacade,
        JellyfishCompanyUserMapperInterface $jellyfishCompanyUserMapper,
        AdapterInterface $adapter,
        array $validatorPlugins
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->jellyfishCompanyUserMapper = $jellyfishCompanyUserMapper;
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

            $this->exportById($transfer->getId());
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return bool
     */
    protected function canExport(TransferInterface $transfer): bool
    {

        if ($transfer instanceof EventEntityTransfer === false ||
            count($transfer->getModifiedColumns()) === 0 ||
            $transfer->getName() !== 'spy_company_user') {

            return false;
        }

        return $this->doValidateExport($transfer);
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function exportById(int $id): void
    {
        $companyUser = $this->companyUserFacade->getCompanyUserById($id);

        $jellyfishCompanyUserTransfer = $this->jellyfishCompanyUserMapper->fromCompanyUser($companyUser);

        $this->adapter->sendRequest($jellyfishCompanyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $transfer
     *
     * @return bool
     */
    protected function doValidateExport(EventEntityTransfer $transfer): bool
    {
        foreach ($this->validatorPlugins as $validatorPlugin) {
            if ($validatorPlugin->validate($transfer) === false) {
                return false;
            }
        }

        return true;
    }
}

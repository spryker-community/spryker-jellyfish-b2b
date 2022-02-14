<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\Jellyfish\Dependency\Service\JellyfishToUtilEncodingServiceInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter\CompanyBusinessUnitAdapter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressChecker;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\JellyfishOrderExpander;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\JellyfishOrderExpanderInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderCommentJellyfishOrderExpander;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderCommentJellyfishOrderExpanderInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderCustomReferenceJellyfishOrderExpander;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderCustomReferenceJellyfishOrderExpanderInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderVatIdJellyfishOrderExpander;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderVatIdJellyfishOrderExpanderInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\CompanyExporter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\CompanyUnitAddressExporter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\ExporterInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitAddressExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitBillingAddressExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitCompanyExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitDataExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitDefaultAddressExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserReferenceFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToLocaleFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BDependencyProvider;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig getConfig()
 */
class JellyfishB2BBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\JellyfishOrderExpanderInterface
     */
    public function createJellyfishOrderExpander(): JellyfishOrderExpanderInterface
    {
        return new JellyfishOrderExpander($this->getCompanyUserReferenceFacade());
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderCommentJellyfishOrderExpanderInterface
     */
    public function createOrderCommentJellyfishOrderExpander(): OrderCommentJellyfishOrderExpanderInterface
    {
        return new OrderCommentJellyfishOrderExpander();
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderCustomReferenceJellyfishOrderExpanderInterface
     */
    public function createOrderCustomReferenceJellyfishOrderExpander(): OrderCustomReferenceJellyfishOrderExpanderInterface
    {
        return new OrderCustomReferenceJellyfishOrderExpander();
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\OrderVatIdJellyfishOrderExpanderInterface
     */
    public function createOrderVatIdJellyfishOrderExpander(): OrderVatIdJellyfishOrderExpanderInterface
    {
        return new OrderVatIdJellyfishOrderExpander(
            $this->getCompanyUnitAddressFacade(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\ExporterInterface
     */
    public function createCompanyExporter(): ExporterInterface
    {
        return new CompanyExporter(
            $this->getCompanyFacade(),
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyExporterExpanderPlugins(),
            $this->createCompanyBusinessUnitAdapter(),
            $this->getExportValidatorPlugins(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\ExporterInterface
     */
    public function createCompanyUnitAddressExporter(): ExporterInterface
    {
        return new CompanyUnitAddressExporter(
            $this->getCompanyUnitAddressFacade(),
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyExporterExpanderPlugins(),
            $this->createCompanyBusinessUnitAdapter(),
            $this->getExportValidatorPlugins(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected function createCompanyBusinessUnitMapper(): JellyfishCompanyBusinessUnitMapperInterface
    {
        return new JellyfishCompanyBusinessUnitMapper(
            $this->createCompanyMapper(),
            $this->createCompanyUnitAddressMapper(),
            $this->createCompanyUnitAddressChecker(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface
     */
    protected function createCompanyMapper(): JellyfishCompanyMapperInterface
    {
        return new JellyfishCompanyMapper($this->getLocaleFacade());
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface
     */
    protected function createCompanyUnitAddressMapper(): JellyfishCompanyUnitAddressMapperInterface
    {
        return new JellyfishCompanyUnitAddressMapper();
    }

    /**
     * @return array
     */
    protected function createCompanyExporterExpanderPlugins(): array
    {
        return [
            $this->createCompanyBusinessUnitDataExpanderPlugin(),
            $this->createCompanyBusinessUnitBillingAddressExpanderPlugin(),
            $this->createCompanyBusinessUnitAddressExpanderPlugin(),
            $this->createCompanyBusinessUnitDefaultAddressExpanderPlugin(),
            $this->createCompanyBusinessUnitCompanyExpanderPlugin(),
        ];
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitDataExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitDataExpanderPlugin(
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyUnitAddressFacade(),
            $this->createCompanyBusinessUnitMapper(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitBillingAddressExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitBillingAddressExpanderPlugin(
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyUnitAddressFacade(),
            $this->createCompanyUnitAddressMapper(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitAddressExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitAddressExpanderPlugin(
            $this->getCompanyUnitAddressFacade(),
            $this->createCompanyUnitAddressMapper(),
            $this->createCompanyUnitAddressChecker(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitDefaultAddressExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitDefaultAddressExpanderPlugin(
            $this->getCompanyBusinessUnitFacade(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface
     */
    protected function createCompanyUnitAddressChecker(): CompanyUnitAddressCheckerInterface
    {
        return new CompanyUnitAddressChecker();
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitCompanyExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitCompanyExpanderPlugin(
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyMapper(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface
     */
    protected function createCompanyBusinessUnitAdapter(): AdapterInterface
    {
        return new CompanyBusinessUnitAdapter(
            $this->getUtilEncodingService(),
            $this->createHttpClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    protected function createHttpClient(): HttpClientInterface
    {
        return new HttpClient([
            'base_uri' => $this->getConfig()->getBaseUri(),
            'timeout' => $this->getConfig()->getTimeout(),
        ]);
    }

    /**
     * @return \FondOfSpryker\Zed\Jellyfish\Dependency\Service\JellyfishToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): JellyfishToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyFacadeInterface
     */
    protected function getCompanyFacade(): JellyfishB2BToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade(): JellyfishB2BToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacade(): JellyfishB2BToCompanyUnitAddressFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY_UNIT_ADDRESS);
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserReferenceFacadeInterface
     */
    protected function getCompanyUserReferenceFacade(): JellyfishB2BToCompanyUserReferenceFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY_USER_REFERENCE);
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToLocaleFacadeInterface
     */
    protected function getLocaleFacade(): JellyfishB2BToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return array<\FondOfSpryker\Zed\JellyfishB2BExtension\Dependency\Plugin\EventEntityTransferExportValidatorPluginInterface>
     */
    protected function getExportValidatorPlugins(): array
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::PLUGINS_EXPORT_VALIDATOR);
    }
}

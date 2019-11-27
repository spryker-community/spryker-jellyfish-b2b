<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\Jellyfish\Dependency\Service\JellyfishToUtilEncodingServiceInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter\CompanyBusinessUnitAdapter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter\CompanyUserAdapter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressChecker;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Checker\CompanyUnitAddressCheckerInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\JellyfishOrderExpander;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Expander\JellyfishOrderExpanderInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\CompanyBusinessUnitExporter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\CompanyExporter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\CompanyUnitAddressExporter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\CompanyUserExporter;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\ExporterInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCustomerMapper;
use FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCustomerMapperInterface;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitAddressExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitCompanyExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Communication\Plugin\JellyfishCompanyBusinessUnitDataExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserReferenceFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCustomerFacadeInterface;
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
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\ExporterInterface
     */
    public function createCompanyExporter(): ExporterInterface
    {
        return new CompanyExporter(
            $this->getCompanyFacade(),
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyExporterExpanderPlugins(),
            $this->createCompanyBusinessUnitAdapter(),
            $this->getExportValidatorPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\ExporterInterface
     */
    public function createCompanyUserExporter(): ExporterInterface
    {
        return new CompanyUserExporter(
            $this->getCompanyUserFacade(),
            $this->createCompanyUserMapper(),
            $this->createCompanyUserAdapter(),
            $this->getExportValidatorPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyUserMapperInterface
     */
    protected function createCompanyUserMapper(): JellyfishCompanyUserMapperInterface
    {
        return new JellyfishCompanyUserMapper(
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyMapper(),
            $this->createCustomerMapper()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Exporter\ExporterInterface
     */
    public function createCompanyBusinessUnitExporter(): ExporterInterface
    {
        return new CompanyBusinessUnitExporter(
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyExporterExpanderPlugins(),
            $this->createCompanyBusinessUnitAdapter(),
            $this->getExportValidatorPlugins()
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
            $this->getExportValidatorPlugins()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCustomerMapperInterface
     */
    public function createCustomerMapper(): JellyfishCustomerMapperInterface
    {
        return new JellyfishCustomerMapper();
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected function createCompanyBusinessUnitMapper(): JellyfishCompanyBusinessUnitMapperInterface
    {
        return new JellyfishCompanyBusinessUnitMapper(
            $this->createCompanyMapper(),
            $this->createCompanyUnitAddressMapper(),
            $this->createCompanyUnitAddressChecker()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2B\Business\Model\Mapper\JellyfishCompanyMapperInterface
     */
    protected function createCompanyMapper(): JellyfishCompanyMapperInterface
    {
        return new JellyfishCompanyMapper();
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
            $this->createCompanyBusinessUnitAddressExpanderPlugin(),
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
            $this->createCompanyBusinessUnitMapper()
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
            $this->createCompanyUnitAddressChecker()
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
            $this->createCompanyMapper()
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
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            $this->getConfig()->dryRun()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface
     */
    protected function createCompanyUserAdapter(): AdapterInterface
    {
        return new CompanyUserAdapter(
            $this->getUtilEncodingService(),
            $this->createHttpClient(),
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            $this->getConfig()->dryRun()
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
     * @throws
     *
     * @return \FondOfSpryker\Zed\Jellyfish\Dependency\Service\JellyfishToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): JellyfishToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyFacadeInterface
     */
    protected function getCompanyFacade(): JellyfishB2BToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade(): JellyfishB2BToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacade(): JellyfishB2BToCompanyUnitAddressFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY_UNIT_ADDRESS);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCustomerFacadeInterface
     */
    protected function getCustomerFacade(): JellyfishB2BToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserFacadeInterface
     */
    protected function getCompanyUserFacade(): JellyfishB2BToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2B\Dependency\Facade\JellyfishB2BToCompanyUserReferenceFacadeInterface
     */
    protected function getCompanyUserReferenceFacade(): JellyfishB2BToCompanyUserReferenceFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::FACADE_COMPANY_USER_REFERENCE);
    }

    /**
     * @return array
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function getExportValidatorPlugins(): array
    {
        return $this->getProvidedDependency(JellyfishB2BDependencyProvider::EXPORT_VALIDATOR_PLUGINS);
    }
}

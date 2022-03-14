<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter;

use FondOfSpryker\Zed\JellyfishB2B\Dependency\Service\JellyfishB2BToUtilEncodingServiceInterface;
use FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class CompanyBusinessUnitAdapter implements CompanyBusinessUnitAdapterInterface
{
    /**
     * @var string
     */
    protected const COMPANY_BUSINESS_UNITS_URI = 'standard/company-business-units';

    /**
     * @var array<string, string>
     */
    protected const DEFAULT_HEADERS = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Service\JellyfishB2BToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Service\JellyfishB2BToUtilEncodingServiceInterface $utilEncodingService
     * @param \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig $config
     * @param \GuzzleHttp\ClientInterface $client
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        JellyfishB2BToUtilEncodingServiceInterface $utilEncodingService,
        JellyfishB2BConfig $config,
        ClientInterface $client,
        LoggerInterface $logger
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->config = $config;
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Psr\Http\Message\StreamInterface|null
     */
    public function sendRequest(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): ?StreamInterface {
        $options = $this->createOptions($jellyfishCompanyBusinessUnitTransfer);

        if ($this->config->dryRun()) {
            $this->logger->error($options[RequestOptions::BODY]);

            return null;
        }

        try {
            $response = $this->client->request('POST', static::COMPANY_BUSINESS_UNITS_URI, $options);

            return $response->getBody();
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Could not export company business unit'),
                [
                    'exception' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                    'data' => $options[RequestOptions::BODY],
                ],
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return array
     */
    protected function createOptions(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): array {
        $options = [];

        $options[RequestOptions::HEADERS] = static::DEFAULT_HEADERS;
        if (!empty($this->config->getUsername()) && !empty($this->config->getPassword())) {
            $options[RequestOptions::AUTH] = [
                $this->config->getUsername(),
                $this->config->getPassword(),
            ];
        }

        $options[RequestOptions::TIMEOUT] = $this->config->getTimeout();

        $options[RequestOptions::BODY] = $this->utilEncodingService->encodeJson(
            $jellyfishCompanyBusinessUnitTransfer->toArray(true, true),
        );

        return $options;
    }
}

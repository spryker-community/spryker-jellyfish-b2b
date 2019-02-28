<?php

namespace FondOfSpryker\Zed\JellyfishB2B\Business\Api\Adapter;

use FondOfSpryker\Zed\JellyfishB2B\Dependency\Service\JellyfishB2BToUtilEncodingServiceInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Shared\Log\LoggerTrait;

abstract class AbstractAdapter implements AdapterInterface
{
    use LoggerTrait;

    protected const DEFAULT_HEADERS = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\JellyfishB2BConfig
     */
    protected $config;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2B\Dependency\Service\JellyfishB2BToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var bool
     */
    protected $dryRun;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2B\Dependency\Service\JellyfishB2BToUtilEncodingServiceInterface $utilEncodingService
     * @param \GuzzleHttp\ClientInterface $client
     * @param bool $dryRun
     */
    public function __construct(
        JellyfishB2BToUtilEncodingServiceInterface $utilEncodingService,
        ClientInterface $client,
        bool $dryRun = false
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->client = $client;
        $this->dryRun = $dryRun;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function sendRequest(AbstractTransfer $transfer): ?StreamInterface
    {
        if ($this->dryRun === true) {
            return null;
        }

        $options = $this->createOptions($transfer);
        $response = $this->send($options);

        $this->handleResponse($response, $transfer);

        return $response->getBody();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return array
     */
    protected function createOptions(AbstractTransfer $transfer): array
    {
        $options = [];

        $options[RequestOptions::HEADERS] = static::DEFAULT_HEADERS;
        $options[RequestOptions::BODY] = $this->utilEncodingService->encodeJson($transfer->toArray(true, true));

        return $options;
    }

    /**
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function send(array $options = []): ResponseInterface
    {
        $uri = $this->getUri();
        $optionsAsJson = $this->utilEncodingService->encodeJson($options);
        $logMessage = sprintf('API request [%s]: %s', $uri, $optionsAsJson);

        $this->getLogger()->info($logMessage);

        return $this->client->post($uri, $options);
    }

    /**
     * @return string
     */
    abstract protected function getUri(): string;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return void
     */
    abstract protected function handleResponse(ResponseInterface $response, AbstractTransfer $transfer): void;
}

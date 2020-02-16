<?php

namespace CurrencyRate\CurrencyRateSource\HttpClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

abstract class CurrencyRateApiClient implements CurrencyRateApiClientInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * CbrClientCurrencyRate constructor.
     *
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $endpoint
     * @param array  $query
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    protected function doGet(string $endpoint, array $query): ResponseInterface
    {
        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $options
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    private function request(string $method, string $endpoint, array $options): ResponseInterface
    {
        $options['base_uri'] = $this->getBaseUrl();
        return $this->httpClient->request($method, $endpoint, $options);
    }

    /**
     * @return string
     */
    abstract protected function getBaseUrl(): string;
}
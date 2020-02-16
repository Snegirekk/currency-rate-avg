<?php

namespace CurrencyRate\CurrencyRateSource;

use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateSource\HttpClient\CurrencyRateApiClientInterface;
use CurrencyRate\Exception\ApiClientException;
use DateTime;

class RbcRateSource implements CurrencyRateSourceInterface
{
    /**
     * @var CurrencyRateApiClientInterface
     */
    private $apiClient;

    /**
     * CbrRateSource constructor.
     *
     * @param CurrencyRateApiClientInterface $apiClient
     */
    public function __construct(CurrencyRateApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * @inheritDoc
     */
    public function provide(CurrencyPair $pair, DateTime $date): CurrencyRate
    {
        $response = $this->apiClient->getRate($pair, $date);

        if ($response->getStatusCode() !== 200) {
            throw ApiClientException::onNonSuccessResponce();
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['data']['rate1'])) {
            throw ApiClientException::onBadResponseBody();
        }

        return new CurrencyRate($pair, $data['data']['rate1']);
    }

    /**
     * @inheritDoc
     */
    public function supports(CurrencyPair $pair): bool
    {
        // TODO: Implement supports() method.
    }
}
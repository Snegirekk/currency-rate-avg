<?php

namespace CurrencyRate\CurrencyRateSource;

use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateSource\HttpClient\CurrencyRateApiClientInterface;
use CurrencyRate\Exception\ApiClientException;
use DateTime;
use Exception;
use SimpleXMLElement;

class CbrRateSource implements CurrencyRateSourceInterface
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

        try {
            $data = new SimpleXMLElement($response->getBody()->getContents());
        } catch (Exception $exception) {
            throw ApiClientException::onBadResponseBody();
        }

        foreach ($data->children() as $item) {
            if ((string) $item->CharCode === $pair->getFrom()) {
                $value = (float) str_replace(',', '.', $item->Value);
                return new CurrencyRate($pair, round($value, 4));
            }
        }

        throw ApiClientException::onBadResponseBody();
    }

    /**
     * @inheritDoc
     */
    public function supports(CurrencyPair $pair): bool
    {
        // TODO: Implement supports() method.
    }
}
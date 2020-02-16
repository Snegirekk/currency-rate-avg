<?php

namespace CurrencyRate\CurrencyRateSource;

use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateSource\HttpClient\CurrencyRateApiClientInterface;
use DateTime;
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
        $data     = new SimpleXMLElement($response->getBody()->getContents());

        foreach ($data->children() as $item) {
            if ((string) $item->CharCode === $pair->getFrom()) {
                $value = (float) str_replace(',', '.', $item->Value);
                return new CurrencyRate($pair, round($value, 4));
            }
        }

        // TODO: throw exception
    }

    /**
     * @inheritDoc
     */
    public function supports(CurrencyPair $pair): bool
    {
        // TODO: Implement supports() method.
    }
}
<?php

namespace CurrencyRate\CurrencyRateSource\HttpClient;

use CurrencyRate\Currency\CurrencyPair;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class RbcApiClient extends CurrencyRateApiClient
{
    /**
     * @param CurrencyPair $pair
     * @param DateTime     $date
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function getRate(CurrencyPair $pair, DateTime $date): ResponseInterface
    {
        return $this->doGet('cash/json/converter_currency_rate', [
            'source'        => 'cbrf',
            'sum'           => 1,
            'currency_from' => $pair->getFrom(),
            'currency_to'   => $pair->getTo(),
            'date'          => $date->format('Y-m-d'),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getBaseUrl(): string
    {
        return 'https://cash.rbc.ru/';
    }
}
<?php

namespace CurrencyRate\CurrencyRateSource\HttpClient;

use CurrencyRate\Currency\CurrencyPair;
use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class CbrApiClient extends CurrencyRateApiClient
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
        return $this->doGet('development/SXML', [
            'date_req' => $date->format('d/m/Y'),
        ]);
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return 'https://www.cbr.ru/';
    }
}
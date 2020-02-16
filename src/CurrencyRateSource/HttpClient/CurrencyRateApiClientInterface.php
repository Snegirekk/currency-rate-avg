<?php

namespace CurrencyRate\CurrencyRateSource\HttpClient;

use CurrencyRate\Currency\CurrencyPair;
use DateTime;
use Psr\Http\Message\ResponseInterface;

interface CurrencyRateApiClientInterface
{
    /**
     * @param CurrencyPair $pair
     * @param DateTime     $date
     *
     * @return ResponseInterface
     */
    public function getRate(CurrencyPair $pair, DateTime $date): ResponseInterface;
}
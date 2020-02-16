<?php

namespace CurrencyRate\CurrencyRateSource;

use CurrencyRate\CurrencyRateSource\HttpClient\CbrApiClient;
use CurrencyRate\CurrencyRateSource\HttpClient\RbcApiClient;
use GuzzleHttp\Client;

class CurrencyRateSourceFactory implements CurrencyRateSourceFactoryInterface
{
    public function getCurrencyRateSource(string $type): CurrencyRateSourceInterface
    {
        $httpClient = new Client();

        switch ($type) {
            case CbrRateSource::class:
                $apiClient = new CbrApiClient($httpClient);
                return new CbrRateSource($apiClient);
            case RbcRateSource::class:
                $apiClient = new RbcApiClient($httpClient);
                return new RbcRateSource($apiClient);
        }

        // TODO: throw unknown source type exception
    }
}
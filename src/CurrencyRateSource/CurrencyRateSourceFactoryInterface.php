<?php

namespace CurrencyRate\CurrencyRateSource;

interface CurrencyRateSourceFactoryInterface
{
    /**
     * @param string $type
     * @return CurrencyRateSourceInterface
     */
    public function getCurrencyRateSource(string $type): CurrencyRateSourceInterface;
}
<?php

namespace CurrencyRate\CurrencyRateSource;

use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use DateTime;

interface CurrencyRateSourceInterface
{
    /**
     * @param CurrencyPair $pair
     * @param DateTime     $date
     *
     * @return CurrencyRate
     */
    public function provide(CurrencyPair $pair, DateTime $date): CurrencyRate;

    /**
     * @param CurrencyPair $pair
     *
     * @return bool
     */
    public function supports(CurrencyPair $pair): bool;
}
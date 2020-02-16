<?php

namespace CurrencyRate\CurrencyRateSource;

use CurrencyRate\Currency\CurrencyPair;
use DateTime;

interface CurrencyRateSourceInterface
{
    /**
     * @param CurrencyPair $pair
     * @param DateTime     $date
     *
     * @return float
     */
    public function provide(CurrencyPair $pair, DateTime $date): float;

    /**
     * @param CurrencyPair $pair
     *
     * @return bool
     */
    public function supports(CurrencyPair $pair): bool;
}
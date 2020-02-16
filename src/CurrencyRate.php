<?php

namespace CurrencyRate;

use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\CurrencyRateSource\CurrencyRateSourceInterface;
use DateTime;

class CurrencyRate
{
    /**
     * @var CurrencyRateSourceInterface[]
     */
    private $sources;

    /**
     * CurrencyRate constructor.
     *
     * @param CurrencyRateSourceInterface[] $sources
     */
    public function __construct(iterable $sources)
    {
        $this->sources = $sources;
    }

    /**
     * @param CurrencyPair  $pair
     * @param DateTime|null $date
     *
     * @return float
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getAverage(CurrencyPair $pair, DateTime $date = null): float
    {
        if (is_null($date)) {
            $date = new DateTime();
        }

        $sum   = 0.0000;
        $count = 0;

        foreach ($this->sources as $source) {
            $sum += $source->provide($pair, $date);
            ++$count;
        }

        return round($sum / $count, 4);
    }
}
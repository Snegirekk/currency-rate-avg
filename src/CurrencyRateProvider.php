<?php

namespace CurrencyRate;

use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateSource\CurrencyRateSourceInterface;
use DateTime;

class CurrencyRateProvider
{
    /**
     * @var CurrencyRateSourceInterface[]
     */
    private $sources;

    /**
     * CurrencyRateProvider constructor.
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
     * @return CurrencyRate
     * @noinspection PhpDocMissingThrowsInspection
     */
    public function getAverage(CurrencyPair $pair, DateTime $date = null): CurrencyRate
    {
        if (is_null($date)) {
            $date = new DateTime();
        }

        $sum   = 0.0000;
        $count = 0;

        foreach ($this->sources as $source) {
            $rate = $source->provide($pair, $date);

            $sum += $rate->getValue();
            ++$count;
        }

        $average = round($sum / $count, 4);

        return new CurrencyRate($pair, $average);
    }
}
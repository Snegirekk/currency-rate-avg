<?php

namespace CurrencyRate\Currency;

use CurrencyRate\Exception\CurrencyException;

final class CurrencyRate
{
    /**
     * @var CurrencyPair
     */
    private $currencyPair;

    /**
     * @var float
     */
    private $value;

    /**
     * CurrencyRateProvider constructor.
     *
     * @param CurrencyPair $currencyPair
     * @param float        $value
     *
     * @throws CurrencyException
     */
    public function __construct(CurrencyPair $currencyPair, float $value)
    {
        if ($value <= 0) {
            throw CurrencyException::onInvalidRateValue($value);
        }

        $this->currencyPair = $currencyPair;
        $this->value        = $value;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->currencyPair->getFrom();
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->currencyPair->getTo();
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s: %s', $this->currencyPair, $this->value);
    }
}
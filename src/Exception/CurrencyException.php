<?php

namespace CurrencyRate\Exception;

use InvalidArgumentException;

class CurrencyException extends InvalidArgumentException
{
    public static function onInvalidCurrencyAbbreviation(string $from, string $to)
    {
        return new self(sprintf('Can not create a currency pair from "%s" and "%s". Must be a three-letter currency abbreviation.', $from, $to));
    }

    public static function onSameCurrenciesInPair(string $currency)
    {
        return new self(sprintf('Can not create a currency pair from "%1$s" and "%1$s". Must be different.', $currency));
    }

    public static function onInvalidRateValue(float $value)
    {
        return new self(sprintf('Can not create a currency rate with value "%f". Must be greater than zero.', $value));
    }
}
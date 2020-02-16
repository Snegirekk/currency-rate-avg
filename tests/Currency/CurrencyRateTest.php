<?php

namespace CurrencyRate\Tests\Currency;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\Exception\CurrencyException;
use PHPUnit\Framework\TestCase;

class CurrencyRateTest extends TestCase
{
    public function testInvalidArgument()
    {
        $currencyPair = new CurrencyPair(Currency::EUR, Currency::USD);

        $this->expectException(CurrencyException::class);
        new CurrencyRate($currencyPair, 0.0000);
    }
}

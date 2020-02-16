<?php

namespace CurrencyRate\Tests\Currency;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Exception\CurrencyException;
use PHPUnit\Framework\TestCase;

class CurrencyPairTest extends TestCase
{
    public function testSameCurrencies()
    {
        $this->expectException(CurrencyException::class);
        new CurrencyPair(Currency::USD, Currency::USD);
    }

    public function testBadCurrencyName()
    {
        $this->expectException(CurrencyException::class);
        new CurrencyPair(Currency::USD, 'USUDE');
    }
}

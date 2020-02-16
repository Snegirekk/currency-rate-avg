<?php

namespace CurrencyRate\Tests\Currency;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Exception\CurrencyException;
use PHPUnit\Framework\TestCase;

class CurrencyPairTest extends TestCase
{
    public function testInvalidArgument()
    {
        $this->expectException(CurrencyException::class);
        new CurrencyPair(Currency::USD, Currency::USD);
    }
}

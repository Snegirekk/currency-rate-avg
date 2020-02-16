<?php

namespace CurrencyRate\Tests;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\CurrencyRate;
use CurrencyRate\CurrencyRateSource\CurrencyRateSourceInterface;
use PHPUnit\Framework\TestCase;

class CurrencyRateTest extends TestCase
{
    /**
     * @dataProvider currencyPairProvider
     *
     * @param CurrencyPair $currencyPair
     * @param float        $rate1
     * @param float        $rate2
     */
    public function testGetAverage(CurrencyPair $currencyPair, float $rate1, float $rate2)
    {
        $currencyRate = new CurrencyRate([
            $this->createSourceStub($rate1),
            $this->createSourceStub($rate2),
        ]);

        $expectedResult = round(($rate1 + $rate2) / 2, 4);
        $actualResult   = $currencyRate->getAverage($currencyPair);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function currencyPairProvider(): array
    {
        return [
            [new CurrencyPair(Currency::USD, Currency::RUR), 64.322, 64.32],
            [new CurrencyPair(Currency::EUR, Currency::RUR), 69.6288, 69.6289],
            [new CurrencyPair(Currency::RUR, Currency::RUR), 1.0000, 1.0000],
        ];
    }

    private function createSourceStub(float $providedValue, bool $supports = true)
    {
        $stub = $this->createMock(CurrencyRateSourceInterface::class);

        $stub
            ->method('provide')
            ->willReturn($providedValue);

        $stub
            ->method('supports')
            ->willReturn($supports);

        return $stub;
    }
}

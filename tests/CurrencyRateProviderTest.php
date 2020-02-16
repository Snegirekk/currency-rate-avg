<?php

namespace CurrencyRate\Tests;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateProvider;
use CurrencyRate\CurrencyRateSource\CurrencyRateSourceInterface;
use PHPUnit\Framework\TestCase;

class CurrencyRateProviderTest extends TestCase
{
    /**
     * @dataProvider currencyPairProvider
     *
     * @param CurrencyPair $currencyPair
     * @param CurrencyRate $rate1
     * @param CurrencyRate $rate2
     */
    public function testGetAverage(CurrencyPair $currencyPair, CurrencyRate $rate1, CurrencyRate $rate2)
    {
        $currencyRate = new CurrencyRateProvider([
            $this->createSourceStub($rate1),
            $this->createSourceStub($rate2),
        ]);

        $expectedResult = round(($rate1->getValue() + $rate2->getValue()) / 2, 4);
        $actualResult   = $currencyRate->getAverage($currencyPair);

        $this->assertInstanceOf(CurrencyRate::class, $actualResult);
        $this->assertEquals($expectedResult, $actualResult->getValue());
    }

    public function currencyPairProvider(): array
    {
        $pair1 = new CurrencyPair(Currency::USD, Currency::RUR);
        $pair2 = new CurrencyPair(Currency::EUR, Currency::RUR);
        $pair3 = new CurrencyPair(Currency::RUR, Currency::RUR);

        return [
            [$pair1, new CurrencyRate($pair1, 64.322), new CurrencyRate($pair1, 64.32)],
            [$pair2, new CurrencyRate($pair2, 69.6288), new CurrencyRate($pair2, 69.6289)],
            [$pair3, new CurrencyRate($pair3, 1.0000), new CurrencyRate($pair3, 1.0000)],
        ];
    }

    private function createSourceStub(CurrencyRate $providedValue, bool $supports = true)
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

<?php

namespace CurrencyRate\Tests;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateProvider;
use CurrencyRate\CurrencyRateSource\CurrencyRateSourceInterface;
use CurrencyRate\Exception\CurrencyRateProviderException;
use DateTime;
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
        $currencyRateProvider = new CurrencyRateProvider([
            $this->createSourceStub($rate1),
            $this->createSourceStub($rate2),
        ]);

        $expectedResult = round(($rate1->getValue() + $rate2->getValue()) / 2, 4);
        $actualResult   = $currencyRateProvider->getAverage($currencyPair);

        $this->assertInstanceOf(CurrencyRate::class, $actualResult);
        $this->assertEquals($expectedResult, $actualResult->getValue());
    }

    public function testInvalidDate()
    {
        $currencyRateProvider = new CurrencyRateProvider([
            $this->createMock(CurrencyRateSourceInterface::class),
        ]);

        $this->expectException(CurrencyRateProviderException::class);
        $currencyRateProvider->getAverage(
            new CurrencyPair(Currency::EUR, Currency::RUR),
            new DateTime('01-01-3568')
        );
    }

    public function currencyPairProvider(): array
    {
        $pair1 = new CurrencyPair(Currency::USD, Currency::RUR);
        $pair2 = new CurrencyPair(Currency::EUR, Currency::RUR);

        return [
            [$pair1, new CurrencyRate($pair1, 64.322), new CurrencyRate($pair1, 64.32)],
            [$pair2, new CurrencyRate($pair2, 69.6288), new CurrencyRate($pair2, 69.6289)],
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

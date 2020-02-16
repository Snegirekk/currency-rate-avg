<?php

namespace CurrencyRate\Tests\CurrencyRateSource;

use CurrencyRate\CurrencyRateSource\CbrRateSource;
use CurrencyRate\CurrencyRateSource\CurrencyRateSourceFactory;
use CurrencyRate\CurrencyRateSource\RbcRateSource;
use PHPUnit\Framework\TestCase;

class CurrencyRateSourceFactoryTest extends TestCase
{
    /**
     * @var CurrencyRateSourceFactory
     */
    private $rateSourceFactory;

    /**
     * @dataProvider sourceTypeProvider
     *
     * @param string $sourceType
     */
    public function testGetCurrencyRateSource(string $sourceType)
    {
        $this->assertInstanceOf($sourceType, $this->rateSourceFactory->getCurrencyRateSource($sourceType));
    }

    /**
     * @return array
     */
    public function sourceTypeProvider(): array
    {
        return [
            [CbrRateSource::class],
            [RbcRateSource::class],
        ];
    }

    protected function setUp()
    {
        $this->rateSourceFactory = new CurrencyRateSourceFactory();
    }

}

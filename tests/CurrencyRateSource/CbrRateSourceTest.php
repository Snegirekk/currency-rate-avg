<?php

namespace CurrencyRate\Tests\CurrencyRateSource;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateSource\CbrRateSource;
use CurrencyRate\CurrencyRateSource\HttpClient\CurrencyRateApiClientInterface;
use DateTime;
use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CbrRateSourceTest extends TestCase
{
    /**
     * @var CbrRateSource
     */
    private $cbrRateSource;

    /**
     * @dataProvider currencyPairProvider
     *
     * @param CurrencyPair $pair
     * @param float        $value
     *
     * @throws Exception
     */
    public function testProvide(CurrencyPair $pair, float $value)
    {
        $rate = $this->cbrRateSource->provide($pair, new DateTime());

        $this->assertInstanceOf(CurrencyRate::class, $rate);
        $this->assertEquals($pair->getFrom(), $rate->getFrom());
        $this->assertEquals($pair->getTo(), $rate->getTo());
        $this->assertEquals($value, $rate->getValue());
    }

    public function currencyPairProvider(): array
    {
        return [
            [new CurrencyPair(Currency::USD, Currency::RUR), 63.4720],
            [new CurrencyPair(Currency::EUR, Currency::RUR), 69.6288],
            [new CurrencyPair(Currency::RUR, Currency::RUR), 1.0000],
        ];
    }

    protected function setUp()
    {
        $response = new Response(200, [], file_get_contents(__DIR__ . '/../resources/cbr_response_body.xml'));

        $apiClient = $this->createMock(CurrencyRateApiClientInterface::class);
        $apiClient
            ->method('getRate')
            ->willReturn($response);

        $this->cbrRateSource = new CbrRateSource($apiClient);
    }
}

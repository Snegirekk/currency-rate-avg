<?php

namespace CurrencyRate\Tests\CurrencyRateSource;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateSource\CbrRateSource;
use CurrencyRate\CurrencyRateSource\HttpClient\CurrencyRateApiClientInterface;
use CurrencyRate\Exception\ApiClientException;
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

    public function testUnavailableServiceException()
    {
        $pair = new CurrencyPair(Currency::USD, Currency::EUR);

        $this->expectException(ApiClientException::class);
        $this->expectExceptionMessage('Couldn\'t get a success response.');
        $this->cbrRateSource->provide($pair, new DateTime('01-01-1970'));
    }

    public function testUnexpectedResponseBodyException()
    {
        $pair = new CurrencyPair(Currency::USD, Currency::EUR);

        $this->expectException(ApiClientException::class);
        $this->expectExceptionMessage('Unexpected response body.');
        $this->cbrRateSource->provide($pair, new DateTime('01-01-2014'));
    }

    public function currencyPairProvider(): array
    {
        return [
            [new CurrencyPair(Currency::USD, Currency::RUR), 63.4720],
            [new CurrencyPair(Currency::EUR, Currency::RUR), 69.6288],
        ];
    }

    protected function setUp()
    {
        $apiClient = $this->createMock(CurrencyRateApiClientInterface::class);
        $apiClient
            ->method('getRate')
            ->will(
                $this->returnCallback(function (CurrencyPair $pair, DateTime $date) {
                    switch ($date->format('Y')) {
                        case '1970':
                            return new Response(500);
                        case '2014':
                            return new Response(200, [], file_get_contents(__DIR__ . '/../resources/cbr_invalid_response_body.xml'));
                        default:
                            return new Response(200, [], file_get_contents(__DIR__ . '/../resources/cbr_response_body.xml'));
                    }
                })
            );

        $this->cbrRateSource = new CbrRateSource($apiClient);
    }
}

<?php

namespace CurrencyRate\Tests\CurrencyRateSource;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\Currency\CurrencyRate;
use CurrencyRate\CurrencyRateSource\HttpClient\CurrencyRateApiClientInterface;
use CurrencyRate\CurrencyRateSource\RbcRateSource;
use CurrencyRate\Exception\ApiClientException;
use DateTime;
use Exception;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class RbcRateSourceTest extends TestCase
{
    /**
     * @var RbcRateSource
     */
    private $rbcRateSource;

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
        $rate = $this->rbcRateSource->provide($pair, new DateTime());

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
        $this->rbcRateSource->provide($pair, new DateTime('01-01-1970'));
    }

    public function testUnexpectedResponseBodyException()
    {
        $pair = new CurrencyPair(Currency::USD, Currency::EUR);

        $this->expectException(ApiClientException::class);
        $this->expectExceptionMessage('Unexpected response body.');
        $this->rbcRateSource->provide($pair, new DateTime('01-01-2014'));
    }

    public function currencyPairProvider(): array
    {
        return [
            [new CurrencyPair(Currency::USD, Currency::RUR), 63.4536],
            [new CurrencyPair(Currency::EUR, Currency::RUR), 68.7710],
        ];
    }

    protected function setUp()
    {
        $apiClient = $this->createMock(CurrencyRateApiClientInterface::class);
        $apiClient
            ->method('getRate')
            ->will(
                $this->returnCallback(function (CurrencyPair $pair, DateTime $date) {
                    if ($date->format('Y') === '1970') {
                        return new Response(500);
                    } elseif ($date->format('Y') === '2014') {
                        return new Response(200, [], json_encode(['invalid_body' => true]));
                    } else {
                        switch ($pair->getFrom()) {
                            case Currency::USD:
                                return new Response(200, [], file_get_contents(__DIR__ . '/../resources/rbc_usd_response_body.json'));
                                break;
                            case Currency::EUR:
                                return new Response(200, [], file_get_contents(__DIR__ . '/../resources/rbc_eur_response_body.json'));
                                break;
                        }
                    }
                })
            );

        $this->rbcRateSource = new RbcRateSource($apiClient);
    }
}

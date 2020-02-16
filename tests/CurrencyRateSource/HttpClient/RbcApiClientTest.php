<?php

namespace CurrencyRate\Tests\CurrencyRateSource\HttpClient;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\CurrencyRateSource\HttpClient\RbcApiClient;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class RbcApiClientTest extends TestCase
{
    /**
     * @var RbcApiClient
     */
    private $apiClient;

    public function testGetRate()
    {
        $response = $this->apiClient->getRate(new CurrencyPair(Currency::USD, Currency::RUR), new DateTime());
        $this->assertInstanceOf(Response::class, $response);

        return $response;
    }

    protected function setUp()
    {
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../../resources/rbc_usd_response_body.json')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient   = new Client(['handler' => $handlerStack]);

        $this->apiClient = new RbcApiClient($httpClient);
    }
}

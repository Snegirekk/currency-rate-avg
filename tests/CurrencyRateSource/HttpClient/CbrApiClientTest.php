<?php

namespace CurrencyRate\Tests\CurrencyRateSource\HttpClient;

use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\CurrencyRateSource\HttpClient\CbrApiClient;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;

class CbrApiClientTest extends TestCase
{
    /**
     * @var CbrApiClient
     */
    private $apiClient;

    public function testGetRate()
    {
        $response = $this->apiClient->getRate(new CurrencyPair(Currency::EUR, Currency::RUR), new DateTime());
        $this->assertInstanceOf(Response::class, $response);

        return $response;
    }

    /**
     * @depends testGetRate
     * @param ResponseInterface $response
     */
    public function testResponseBodyStructure(ResponseInterface $response)
    {
        $data = new SimpleXMLElement($response->getBody()->getContents());

        $this->assertFalse(empty($data));
        $this->assertFalse(empty($data->Valute));
    }

    protected function setUp()
    {
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/../../resources/cbr_response_body.xml')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient   = new Client(['handler' => $handlerStack]);

        $this->apiClient = new CbrApiClient($httpClient);
    }
}

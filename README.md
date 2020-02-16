## Currency Rate Average Lib

A small library that provides an average currency rate based on few external services (RBC and CBR are bundled out of the box).

#### Installation
First add a repo into your `composer.json`:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:Snegirekk/currency-rate-avg.git"
        }
    ]
}
```
Then add the library to the project:
```shell script
$ composer require snegirekk/currency-rate-avg 1.0.0-alpha
```

#### Usage
Instantiate a provider with currency sources created via factory, then get the `CurrencyRate` object and enjoy:
```php
use CurrencyRate\Currency\Currency;
use CurrencyRate\Currency\CurrencyPair;
use CurrencyRate\CurrencyRateProvider;
use CurrencyRate\CurrencyRateSource\CbrRateSource;
use CurrencyRate\CurrencyRateSource\CurrencyRateSourceFactory;
use CurrencyRate\CurrencyRateSource\RbcRateSource;

$sourceFactory = new CurrencyRateSourceFactory();

$currencyRateProvider = new CurrencyRateProvider([
    $sourceFactory->getCurrencyRateSource(CbrRateSource::class),
    $sourceFactory->getCurrencyRateSource(RbcRateSource::class),
]);

$rate = $currencyRateProvider->getAverage(
    new CurrencyPair(Currency::EUR, Currency::RUR),
    new \DateTime('18-06-2014')
);

echo $rate->getFrom(); // EUR
echo $rate->getTo(); // RUR
echo $rate->getValue(); // 16.3597
echo (string) $rate; // EUR/RUR: 16.3597
```

Fill free to implement `\CurrencyRate\CurrencyRateSource\CurrencyRateSourceInterface` and add it to provider.

#### Usage example with Symfony
Register factory, sources and provider in `services.yaml`, then autowire provider in your services or controllers to make things:
```yaml
# services.yaml

currency_rate.source_factory:
    class: CurrencyRate\CurrencyRateSource\CurrencyRateSourceFactory

currency_rate.cbr_source:
    class: CurrencyRate\CurrencyRateSource\CbrRateSource
    factory: ['@currency_rate.source_factory', 'getCurrencyRateSource']
    arguments:
        - CurrencyRate\CurrencyRateSource\CbrRateSource
    tags: [currency_rate_source]

currency_rate.rbc_source:
    class: CurrencyRate\CurrencyRateSource\RbcRateSource
    factory: ['@currency_rate.source_factory', 'getCurrencyRateSource']
    arguments:
        - CurrencyRate\CurrencyRateSource\RbcRateSource
    tags: [currency_rate_source]

CurrencyRate\CurrencyRateProvider:
    arguments:
        - !tagged currency_rate_source
```
```php
public function index(CurrencyRateProvider $rateProvider): Response
{
    $rate = $rateProvider->getAverage(
        new CurrencyPair(Currency::EUR, Currency::RUR), 
        new \DateTime('18-06-2014')
    );
        
    return $this->render('template', [
        'rate' => $rate,
    ]);
}
```
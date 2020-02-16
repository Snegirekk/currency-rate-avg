<?php

namespace CurrencyRate\Exception;

use RuntimeException;

class CurrencyRateProviderException extends RuntimeException
{
    public static function onInvalidDate()
    {
        return new self('The date must not be in future.');
    }
}
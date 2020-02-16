<?php

namespace CurrencyRate\Exception;

use RuntimeException;

class ApiClientException extends RuntimeException
{
    public static function onNonSuccessResponce()
    {
        return new self('Couldn\'t get a success response.');
    }

    public static function onBadResponseBody()
    {
        return new self('Unexpected response body.');
    }
}
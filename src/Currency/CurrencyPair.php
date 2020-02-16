<?php

namespace CurrencyRate\Currency;

use CurrencyRate\Exception\CurrencyException;

final class CurrencyPair
{
    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * CurrencyPair constructor.
     *
     * @param string $from
     * @param string $to
     *
     * @throws CurrencyException
     */
    public function __construct(string $from, string $to)
    {
        if (strlen($from) !== 3 || strlen($to) !== 3) {
            throw CurrencyException::onInvalidCurrencyAbbreviation($from, $to);
        } elseif ($from === $to) {
            throw CurrencyException::onSameCurrenciesInPair($from);
        }

        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s/%s', $this->from, $this->to);
    }
}
<?php

namespace CurrencyRate\Currency;

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
     */
    public function __construct(string $from, string $to)
    {
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
<?php

namespace Rezzza\Accounting\Operation\Amount;

use Rezzza\Accounting\Operation\OperandInterface;

/**
 * Result
 *
 * @uses Price
 * @uses OperandInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Result extends Price implements OperandInterface
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @var float
     */
    protected $complement;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @param float  $value      value
     * @param float  $complement complement
     * @param string $currency   currency
     * @param string $source     source
     */
    public function __construct($value, $complement, $currency, $source)
    {
        $this->value      = $value;
        $this->complement = $complement;
        $this->currency   = $currency;
        $this->source     = $source;
    }

    public function __toString()
    {
        return sprintf('%s ====> value = %s, complement = %s, currency = %s', $this->source, $this->value, $this->complement, $this->currency);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}

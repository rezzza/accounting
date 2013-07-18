<?php

namespace Rezzza\Accounting\Operation\Amount;

/**
 * Result
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Result
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
     */
    public function __construct($value, $complement, $currency)
    {
        $this->value      = $value;
        $this->complement = $complement;
        $this->currency   = $currency;
    }

    /**
     * @return float
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

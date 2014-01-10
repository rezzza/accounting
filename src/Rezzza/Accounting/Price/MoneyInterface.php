<?php

namespace Rezzza\Accounting\Price;

/**
 * MoneyInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface MoneyInterface
{
    /**
     * @return float
     */
    public function getValue();

    /**
     * @return string
     */
    public function getCurrency();
}

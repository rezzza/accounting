<?php

namespace Rezzza\Accounting\Operation;

/**
 * OperandInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface OperandInterface
{
    /**
     * __toString
     */
    public function __toString();

    /**
     * @param string           $operation operation
     * @param OperandInterface $right     right
     *
     * @return OperandInterface
     */
    public function compute($operation, OperandInterface $right);

    /**
     * @return float
     */
    public function getValue();
}

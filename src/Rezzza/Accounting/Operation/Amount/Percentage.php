<?php

namespace Rezzza\Accounting\Operation\Amount;

use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\Operation;

/**
 * Percentage
 *
 * @uses OperandInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Percentage implements OperandInterface
{
    /**
     * @var float|int
     */
    protected $amount;

    /**
     * @param float|int $amount amount
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function compute($operation, $right)
    {
        if (!$right instanceof Percentage) {
            throw new \LogicException(sprintf('Unsupported operand (%s). Percentage operand can only be computed with another Percentage operand.'), get_class($right));
        }

        switch($operation) {
            case Operation::SUB:
                $value = $this->getAmount() - $right->getAmount();
                break;
            case Operation::SUM:
                $value = $this->getAmount() + $right->getAmount();
                break;
            default:
                throw new \LogicException(sprintf('Unsupported operation for Percentage operand (%s).'), $operation);
                break;
        }

        return new Percentage($value);
    }

    public function getAmount()
    {
        return $this->amount;
    }
}

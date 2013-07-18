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
        $this->amount   = $amount;
    }

    public function compute($operation, $right)
    {
        if ($right instanceof Price) {
            throw new \LogicException("Percentage accepts only a percentage in right position.");
        }

        switch($operation) {
            case Operaiton::SUB:
                $value = $this->getAmount() - $right->getAmount();
                break;
            case Operaiton::SUM:
                $value = $this->getAmount() + $right->getAmount();
                break;
            default:
                throw new \LogicException(sprintf('Percentage does not accept operation "%s"', $operation));
                break;
        }

        return new Percentage($value);
    }

    public function getAmount()
    {
        return $this->amount;
    }
}

<?php

namespace Rezzza\Accounting\Operation\Amount;

use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\Operation;

/**
 * Price
 *
 * @uses OperandInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Price implements OperandInterface
{
    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @param float  $amount   amount
     * @param string $currency currency
     */
    public function __construct($amount, $currency)
    {
        $this->amount   = $amount;
        $this->currency = $currency;
    }

    public function compute($operation, $right)
    {
        if ($right instanceof Price) {
            return $this->computeWithPrice($operation, $right);
        } elseif ($right instanceof Percentage) {
            return $this->computeWithPercent($operation, $right);
        } else {
            throw new \LogicException('A price can deal with a Price or a Percentage, here is '.get_class($right));
        }
    }

    /**
     * @param string $operation operation
     * @param Price  $right     right
     *
     * @return Result
     */
    protected function computeWithPrice($operation, Price $right)
    {
        switch($operation) {
            case Operation::SUB:
                $value = $this->getAmount() - $right->getAmount();
                break;
            case Operation::SUM:
                $value = $this->getAmount() + $right->getAmount();
                break;
            default:
                throw new \LogicException(sprintf('Percentage does not accept operation "%s"', $operation));
                break;
        }

        return new Result($value, ($this->getAmount() - $value), $this->getCurrency());
    }

    /**
     * @param string     $operation operation
     * @param Percentage $right     right
     *
     * @return Result
     */
    protected function computeWithPercent($operation, Percentage $right)
    {
        switch($operation) {
            case Operation::SUB:
                $value = $this->getAmount() / (1+($right->getAmount() / 100));
                break;
            case Operation::SUM:
                $value = $this->getAmount() + ($this->getAmount() * ($right->getAmount() / 100));
                break;
            default:
                throw new \LogicException(sprintf('Percentage does not accept operation "%s"', $operation));
                break;
        }

        return new Result($value, ($this->getAmount() - $value), $this->getCurrency());
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}

<?php

namespace Rezzza\Accounting\Operation\Amount;

use Symfony\Component\Intl\Intl;

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
    protected $value;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var integer
     */
    private $fractionDigits;

    /**
     * @param float  $value   value
     * @param string $currency currency
     */
    public function __construct($value, $currency)
    {
        $this->value          = (float) $value;
        $this->currency       = (string) $currency;
        $this->fractionDigits = Intl::getCurrencyBundle()->getFractionDigits($currency);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->value.Intl::getCurrencyBundle()->getCurrencySymbol($this->currency);
    }

    /**
     * @return integer
     */
    public function getFractionDigits()
    {
        return $this->fractionDigits;
    }

    /**
     * {@inheritdoc}
     */
    public function compute($operation, OperandInterface $right)
    {
        if ($right instanceof Price) {
            return $this->computeWithPrice($operation, $right);
        } elseif ($right instanceof Percentage) {
            return $this->computeWithPercent($operation, $right);
        } else {
            throw new \LogicException(sprintf('Unsupported operand (%s). Percentage operand can only be computed with Percentage or Price operands.'), get_class($right));
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
                $value = $this->round($this->getValue() - $right->getValue());
                break;
            case Operation::SUM:
                $value = $this->round($this->getValue() + $right->getValue());
                break;
            default:
                throw new \LogicException(sprintf('Unsupported operation for Price operand (%s).'), $operation);
                break;
        }

        return new Result(
            $value,
            ($this->getValue() - $value),
            $this->getCurrency(),
            (string) $this.' '.$operation.' '.(string) $right
        );
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
                $value = $this->round($this->getValue() / (1+($right->getValue() / 100)));
                break;
            case Operation::SUM:
                $value = $this->round($this->getValue() + ($this->getValue() * ($right->getValue() / 100)));
                break;
            default:
                throw new \LogicException(sprintf('Unsupported operation for Price operand (%s).'), $operation);
                break;
        }

        return new Result(
            $value,
            ($this->getValue() - $value),
            $this->getCurrency(),
            (string) $this.' '.$operation.' '.(string) $right
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }


    /**
     * @return float
     */
    private function round($value)
    {
        return (float) round($value, $this->getFractionDigits(), PHP_ROUND_HALF_UP);
    }
}

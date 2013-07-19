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
    protected $value;

    /**
     * @param float|int $value value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->value.'%';
    }

    /**
     * {@inheritdoc}
     */
    public function compute($operation, OperandInterface $right)
    {
        if (!$right instanceof Percentage) {
            throw new \LogicException(sprintf('Unsupported operand (%s). Percentage operand can only be computed with another Percentage operand.'), get_class($right));
        }

        switch($operation) {
            case Operation::SUB:
                $value = $this->getValue() - $right->getValue();
                break;
            case Operation::SUM:
                $value = $this->getValue() + $right->getValue();
                break;
            default:
                throw new \LogicException(sprintf('Unsupported operation for Percentage operand (%s).'), $operation);
                break;
        }

        return new Percentage($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}

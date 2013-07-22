<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Amount;

use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\Operation;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Percentage extends AbstractOperand implements OperandInterface
{
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
            case Operation::MAJORATE:
                $value = $this->getValue() - $right->getValue();
                break;
            case Operation::MINORATE:
                $value = $this->getValue() + $right->getValue();
                break;
            default:
                throw new \LogicException(sprintf('Unsupported operation for Percentage operand (%s).', $operation));
                break;
        }

        return new Result(
            new Percentage($value),
            new Percentage(100 - $value),
            sprintf('%s %s %s', $this, $operation, $right)
        );
    }
}

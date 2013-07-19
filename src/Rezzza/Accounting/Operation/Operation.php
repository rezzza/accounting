<?php

namespace Rezzza\Accounting\Operation;

use Rezzza\Accounting\Operation\Reference\ReferenceInterface;
use Rezzza\Accounting\Operation\Wrapper\Operand;

class Operation implements OperationInterface
{
    CONST SUB = 'sub';
    CONST SUM = 'sum';

    protected $left;

    protected $operator;

    protected $right;

    public function __construct(OperandInterface $left, $operator, OperandInterface $right)
    {
        $availableOperators = array(self::SUB, self::SUM);

        if (!in_array($operator, $availableOperators)) {
            throw new \InvalidArgumentException(sprintf('Unsupported Operator (%s). Please use one of these: %s', $operator, implode(', ', $availableOperators)));
        }

        $this->left     = $left;
        $this->operator = $operator;
        $this->right    = $right;
    }

    public function compute()
    {
        if ($this->left instanceof OperationInterface) {
            $this->left = $this->left->compute();
        }

        if ($this->right instanceof OperationInterface) {
            $this->right = $this->right->compute();
        }

        return $this->left->compute($this->operator, $this->right);
    }

    public function needsResultsSet()
    {
        return $this->isSideHasReference($this->left) || $this->isSideHasReference($this->right);
    }

    public function setResultsSet(OperationSetResult $resultsSet)
    {
        if ($this->isSideHasReference($this->left)) {
            $this->left->setResultsSet($resultsSet);
        }

        if ($this->isSideHasReference($this->right)) {
            $this->right->setResultsSet($resultsSet);
        }
    }

    public function isSideHasReference($side)
    {
        return $side instanceof ReferenceInterface || ($side instanceof Operand && $side->needsResultsSet());
    }
}

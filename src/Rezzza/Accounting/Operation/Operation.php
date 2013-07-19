<?php

namespace Rezzza\Accounting\Operation;

use Rezzza\Accounting\Operation\Reference\ReferenceInterface;
use Rezzza\Accounting\Operation\Wrapper\Operand;

/**
 * Operation
 *
 * @uses OperationInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Operation implements OperationInterface
{
    CONST REMOVE = 'remove';
    CONST DEDUCT = 'deduct';
    CONST APPEND = '+';

    /**
     * @var OperationInterface|OperandInterface
     */
    protected $left;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var OperationInterface|OperandInterface
     */
    protected $right;

    /**
     * @param OperationInterface|OperandInterface $left     left
     * @param string                              $operator operator
     * @param OperationInterface|OperandInterface $right    right
     */
    public function __construct($left, $operator, $right)
    {
        $availableOperators = array(self::REMOVE, self::DEDUCT, self::APPEND);

        if (!in_array($operator, $availableOperators)) {
            throw new \InvalidArgumentException(sprintf('Unsupported Operator (%s). Please use one of these: %s', $operator, implode(', ', $availableOperators)));
        }

        $this->operator = $operator;
        $this->addSide($left, 'left');
        $this->addSide($right, 'right');

    }

    /**
     * {@inheritdoc}
     */
    public function compute()
    {
        if ($this->left instanceof OperationInterface) {
            // it should return an OperandInterface
            $this->left = $this->left->compute();
        }

        if ($this->right instanceof OperationInterface) {
            // it should return an OperandInterface
            $this->right = $this->right->compute();
        }

        return $this->left->compute($this->operator, $this->right);
    }

    /**
     * {@inheritdoc}
     */
    public function needsResultsSet()
    {
        return $this->isSideHasReference($this->left) || $this->isSideHasReference($this->right);
    }

    /**
     * {@inheritdoc}
     */
    public function setResultsSet(OperationSetResult $resultsSet)
    {
        if ($this->isSideHasReference($this->left)) {
            $this->left->setResultsSet($resultsSet);
        }

        if ($this->isSideHasReference($this->right)) {
            $this->right->setResultsSet($resultsSet);
        }
    }

    /**
     * @param OperationInterface|OperandInterface $side side
     *
     * @return boolean
     */
    private function isSideHasReference($side)
    {
        return $side instanceof ReferenceInterface || ($side instanceof Operand && $side->needsResultsSet());
    }

    /**
     * @param OperationInterface|OperandInterface $data data
     * @param string                              $side side
     */
    private function addSide($data, $side)
    {
        if (!$data instanceof OperationInterface && !$data instanceof OperandInterface) {
            throw new \InvalidArgumentException(sprintf('%s parameter accepts only an instance of OperationInterface|OperandInterface', $side));
        }

        $this->$side = $data;
    }
}

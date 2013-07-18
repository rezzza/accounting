<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation;

use Rezzza\Accounting\Operation\Reference\ReferenceInterface;
use Rezzza\Accounting\Operation\Wrapper\Operand;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Operation implements OperationInterface
{
    CONST MAJORATE  = 'majorate by';
    CONST MINORATE  = 'minorate by';
    CONST EXONERATE = 'exonerate by';

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
        $availableOperators = array(self::MAJORATE, self::MINORATE, self::EXONERATE);

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
        if ($this->left instanceof ReferenceInterface) {
            $this->left = $this->left->getReference();
        }

        if ($this->right instanceof ReferenceInterface) {
            $this->right = $this->right->getReference();
        }

        if ($this->left instanceof OperationInterface) {
            $this->left = $this->left->compute();
        }

        if ($this->right instanceof OperationInterface) {
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
        if (!$data instanceof OperationInterface && !$data instanceof OperandInterface && !$data instanceof ReferenceInterface) {
            throw new \InvalidArgumentException(sprintf('%s parameter accepts only an instance of OperationInterface|OperandInterface|ReferenceInterface', $side));
        }

        $this->$side = $data;
    }
}

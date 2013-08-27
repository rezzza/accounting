<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class OperationSet implements OperationInterface
{
    /**
     * @var array<OperationInterface>
     */
    protected $operations = array();

    /**
     * @var OperationSetResult
     */
    protected $resultsSet;

    /**
     * @param array<OperationInterface>   $operations operations
     * @param array                       $resultsSet resultsSet
     */
    public function __construct(array $operations, OperationSetResult $resultsSet = null)
    {
        foreach ($operations as $k => $operation) {
            $this->add($operation, $k);
        }

        if (null === $resultsSet) {
            $resultsSet = new OperationSetResult();
        }

        $this->setResultsSet($resultsSet);
    }

    /**
     * @param OperationInterface $operation operation
     * @param string|integer     $offset    offset
     */
    public function add(OperationInterface $operation, $offset = null)
    {
        if (null === $offset) {
            $this->operations[] = $operation;
        } else {
            $this->operations[$offset] = $operation;
        }
    }

    /**
     * This operation will logs results on a ResultsSet.
     *
     * {@inheritdoc}
     */
    public function compute()
    {
        $this->getResultsSet()->incrementLevel();

        foreach ($this->operations as $offset => $operation) {
            if ($operation->needsResultsSet()) {
                $operation->setResultsSet($this->getResultsSet());
            }

            $result = $operation->compute();

            if ($this->getResultsSet()->has($offset)) {
                $offset = null;
            }

            $this->getResultsSet()->add(clone $result, $offset);
        }

        $this->getResultsSet()->decrementLevel();

        return $this->getResultsSet()->end();
    }

    /**
     * {@inheritdoc}
     */
    public function setResultsSet(OperationSetResult $resultsSet)
    {
        $this->resultsSet = $resultsSet;
    }

    /**
     * @return OperationSetResult
     */
    public function getResultsSet()
    {
        if (null === $this->resultsSet) {
            $this->resultsSet = new OperationSetResult();
        }

        return $this->resultsSet;
    }

    /**
     * {@inheritdoc}
     */
    public function needsResultsSet()
    {
        return true;
    }
}

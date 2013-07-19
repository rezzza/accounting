<?php

namespace Rezzza\Accounting\Operation;

/**
 * OperationSet
 *
 * @uses OperationInterface
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
     * @param array<OperationInterface> $operations operations
     */
    public function __construct(array $operations)
    {
        foreach ($operations as $k=> $operation) {
            $this->add($operation, $k);
        }
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
        if (null === $this->resultsSet) {
            $this->resultsSet = new OperationSetResult();
        }

        foreach ($this->operations as $offset => $operation) {
            if ($operation->needsResultsSet()) {
                $operation->setResultsSet($this->resultsSet);
            }

            $result = $operation->compute();

            // if this offset is already used in resultsSet ...
            if ($this->resultsSet->has($offset)) {
                $offset = null;
            }

            $this->resultsSet->add($result, $offset);
        }

        return $this->resultsSet->end();
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

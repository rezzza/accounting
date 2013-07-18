<?php

namespace Rezzza\Accounting\Operation;

class OperationSet implements OperationInterface, OperandInterface
{
    protected $operations = array();

    protected $resultsSet;

    public function __construct(array $operations)
    {
        foreach ($operations as $k=> $operation) {
            $this->add($operation, $k);
        }
    }

    public function add(OperationInterface $operation, $offset = null)
    {
        if (null === $offset) {
            $this->operations[] = $operation;
        } else {
            $this->operations[$offset] = $operation;
        }
    }

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

            // if this offset is already used on resultsSet ...
            if ($this->resultsSet->has($offset)) {
                $offset = null;
            }

            $this->resultsSet->add($result, $offset);
        }

        return $this->resultsSet->end();
    }

    public function setResultsSet(OperationSetResult $resultsSet)
    {
        $this->resultsSet = $resultsSet;
    }

    public function getResultsSet()
    {
        return $this->resultsSet;
    }

    public function needsResultsSet()
    {
        return true;
    }
}

<?php

namespace Rezzza\Accounting\Operation;

/**
 * OperationInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface OperationInterface
{
    /**
     * Compute the operation, left to the right with operator.
     *
     * @return OperandInterface
     */
    public function compute();

    /**
     * @return boolean
     */
    public function needsResultsSet();

    /**
     * @param OperationSetResult $resultsSet resultsSet
     */
    public function setResultsSet(OperationSetResult $resultsSet);
}

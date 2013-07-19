<?php

namespace Rezzza\Accounting\Operation\Reference;

use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\OperationSetResult;

/**
 * ReferenceInterface
 *
 * @uses OperandInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface ReferenceInterface
{
    /**
     * Fetch the reference
     */
    public function getReference();

    /**
     * @param OperationSetResult $resultsSet resultsSet
     */
    public function setResultsSet(OperationSetResult $resultsSet);
}

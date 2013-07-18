<?php

namespace Rezzza\Accounting\Operation\Reference;

use Rezzza\Accounting\Operation\OperationSetResult;

/**
 * AbstractReference
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class AbstractReference
{
    /**
     * @var OperationSetResult
     */
    protected $resultsSet;

    /**
     * @param OperationSetResult $resultsSet resultsSet
     */
    public function setResultsSet(OperationSetResult $resultsSet)
    {
        $this->resultsSet = $resultsSet;
    }
}

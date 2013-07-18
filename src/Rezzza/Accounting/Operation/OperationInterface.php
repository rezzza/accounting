<?php

namespace Rezzza\Accounting\Operation;

interface OperationInterface
{
    public function compute();

    public function needsResultsSet();

    public function setResultsSet(OperationSetResult $resultsSet);
}

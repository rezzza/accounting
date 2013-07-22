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

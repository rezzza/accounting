<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Reference;

use Rezzza\Accounting\Operation\OperationSetResult;

/**
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

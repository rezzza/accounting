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
interface OperandInterface
{
    /**
     * __toString
     */
    public function __toString();

    /**
     * @param string           $operation operation
     * @param OperandInterface $right     right
     *
     * @return OperandInterface
     */
    public function compute($operation, OperandInterface $right);

    /**
     * @return float
     */
    public function getValue();

    /**
     * @param integer $level level
     */
    public function setLevel($level);

    /**
     * @return integer
     */
    public function getLevel();
}

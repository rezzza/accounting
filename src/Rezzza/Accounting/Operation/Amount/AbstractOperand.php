<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Amount;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class AbstractOperand
{
    /**
     * @var float
     */
    protected $value;

    /**
     * @var integer
     */
    protected $level = 0;

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param integer $level level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        return (int) $this->level;
    }
}

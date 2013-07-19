<?php

namespace Rezzza\Accounting\Operation\Amount;

/**
 * AbstractOperand
 *
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
        return $this->level;
    }
}

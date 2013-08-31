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
class OperationSetResult
{
    /**
     * @var array
     */
    protected $results = array();

    /**
     * @var float
     */
    protected $level = 0;

    public function __construct(array $resultSet = array())
    {
        foreach ($resultSet as $offset => $value) {
            $this->add($value, $offset);
        }
    }

    /**
     * Render each results as string.
     */
    public function __toString()
    {
        $data = array();

        foreach ($this->results as $offset => $result) {
            $indentation = str_repeat('  ', max(0, ($result->getLevel() - 1)));
            $data[]      = $indentation.sprintf('%s%s: %s', $indentation, $offset, $result);
        }

        return implode(chr(10), $data);
    }

    /**
     * @param OperandInterface $result result
     * @param string|integer   $offset offset
     */
    public function add(OperandInterface $result, $offset = null)
    {
        $result->setLevel($this->level);

        if (null === $offset) {
            $this->results[] = $result;
        } else {
            $this->results[$offset] = $result;
        }
    }

    /**
     * @param string|integer $offset offset
     *
     * @return boolean
     */
    public function has($offset)
    {
        return array_key_exists($offset, $this->results);
    }

    /**
     * @param string|integer $offset offset
     *
     * @return OperandInterface|null
     */
    public function get($offset)
    {
        return $this->has($offset) ? $this->results[$offset] : null;
    }

    /**
     * @return OperandInterface|null
     */
    public function end()
    {
        return end($this->results);
    }

    /**
     * Increment level when compilation of a OperationSet is started.
     */
    public function incrementLevel()
    {
        $this->level++;
    }

    /**
     * Decrement level when compilation of a OperationSet is finished.
     */
    public function decrementLevel()
    {
        $this->level--;
    }
}

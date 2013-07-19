<?php

namespace Rezzza\Accounting\Operation;

/**
 * OperationSetResult
 *
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

    /**
     * Render each results as string.
     */
    public function __toString()
    {
        $data = array();

        foreach ($this->results as $offset => $result) {
            $data[] = str_repeat('--', $result->getLevel()).sprintf('%s => %s', $offset, (string) $result);
        }

        return implode(chr(10), $data);
    }

    /**
     * @param OperandInterface $result result
     * @param string|integer   $offset offset
     */
    public function add($result, $offset = null)
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

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
     * Render each results as string.
     */
    public function __toString()
    {
        $data = array();

        foreach ($this->results as $offset => $result) {
            $data[] = sprintf('%s => %s', $offset, (string) $result);
        }

        return implode(chr(10), $data);
    }

    /**
     * @param OperandInterface $result result
     * @param string|integer   $offset offset
     */
    public function add($result, $offset = null)
    {
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
}

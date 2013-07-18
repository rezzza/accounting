<?php

namespace Rezzza\Accounting\Operation;

class OperationSetResult
{
    protected $results = array();

    public function add($result, $offset = null)
    {
        if (null === $offset) {
            $this->results[] = $result;
        } else {
            $this->results[$offset] = $result;
        }
    }

    public function has($offset)
    {
        return array_key_exists($offset, $this->results);
    }

    public function get($offset)
    {
        return $this->has($offset) ? $this->results[$offset] : null;
    }

    public function end()
    {
        return end($this->results);
    }
}

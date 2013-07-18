<?php

namespace Rezzza\Accounting\Operation\Reference;

/**
 * Reference
 *
 * @uses AbstractReference
 * @uses ReferenceInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Reference extends AbstractReference implements ReferenceInterface
{
    /**
     * @var string|integer
     */
    protected $offset;

    /**
     * @param string|integer $offset offset
     */
    public function __construct($offset)
    {
        $this->offset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getReference()
    {
        if (!$this->resultsSet->has($this->offset)) {
            throw new \Exception(sprintf('Reference with offset "%s" is not exists.', $this->offset));
        }

        return clone $this->resultsSet->get($this->offset);
    }
}

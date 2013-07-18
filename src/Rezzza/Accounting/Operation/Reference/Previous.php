<?php

namespace Rezzza\Accounting\Operation\Reference;

/**
 * Previous
 *
 * @uses AbstractReference
 * @uses ReferenceInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Previous extends AbstractReference implements ReferenceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getReference()
    {
        $result = $this->resultsSet->end();

        if (!$result) {
            throw new \LogicException('Cant fetch last reference');
        }

        return clone $result;
    }
}

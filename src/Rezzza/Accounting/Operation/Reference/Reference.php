<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Reference;

/**
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
            throw new \Exception(sprintf('Reference with offset "%s" does not exist.', $this->offset));
        }

        return clone $this->resultsSet->get($this->offset);
    }
}

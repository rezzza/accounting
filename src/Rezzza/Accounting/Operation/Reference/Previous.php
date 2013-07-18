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
class Previous extends AbstractReference implements ReferenceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getReference()
    {
        $result = $this->resultsSet->end();

        if (!$result) {
            throw new \LogicException('Can not fetch last reference.');
        }

        return clone $result;
    }
}

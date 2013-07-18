<?php

 /**
  * This file is part of the Accounting package.
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @license    MIT License
  */

namespace Rezzza\Accounting\Operation\Wrapper;

use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\Amount\Result;
use Rezzza\Accounting\Operation\Reference\ReferenceInterface;

/**
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Factory
{
    /**
     * @param Result|OperandInterface $result result
     *
     * @return Operand
     */
    public function value($result)
    {
        if (!$result instanceof Result && !$result instanceof ReferenceInterface) {
            throw new \InvalidArgumentException('Method Operation\Wrapper\Factory::val accepts a Operation\Amount\Result or a Operation\Reference\ReferenceInterface');
        }

        return new Operand(Operand::VALUE, $result);
    }

    /**
     * @param Result|OperandInterface $result result
     *
     * @return Operand
     */
    public function complement($result)
    {
        if (!$result instanceof Result && !$result instanceof ReferenceInterface) {
            throw new \InvalidArgumentException('Method Operation\Wrapper\Factory::compl accepts a Operation\Amount\Result or a Operation\Reference\ReferenceInterface');
        }

        return new Operand(Operand::COMPLEMENT, $result);
    }
}

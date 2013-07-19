<?php

namespace Rezzza\Accounting\Operation\Wrapper;

use Rezzza\Accounting\Operation\OperandInterface;
use Rezzza\Accounting\Operation\Amount\Result;
use Rezzza\Accounting\Operation\Reference\ReferenceInterface;

/**
 * Factory
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Factory
{
    /**
     * @param Result|OperandInterface $operand operand
     *
     * @return Operand
     */
    public function val($operand)
    {
        if (!$operand instanceof Result && !$operand instanceof ReferenceInterface) {
            throw new \InvalidArgumentException('Method Operation\Wrapper\Factory::val accepts a Operation\Amount\Result or a Operation\Reference\ReferenceInterface');
        }

        return new Operand(Operand::VALUE, $operand);
    }

    /**
     * @param Result|OperandInterface $operand operand
     *
     * @return Operand
     */
    public function compl($operand)
    {
        if (!$operand instanceof Result && !$operand instanceof ReferenceInterface) {
            throw new \InvalidArgumentException('Method Operation\Wrapper\Factory::compl accepts a Operation\Amount\Result or a Operation\Reference\ReferenceInterface');
        }

        return new Operand(Operand::COMPLEMENT, $operand);
    }
}

<?php

namespace Rezzza\Accounting\Operation\Wrapper;

use Rezzza\Accounting\Operation\OperandInterface;

/**
 * Factory
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Factory
{
    /**
     * @param OperandInterface $operand operand
     *
     * @return Operand
     */
    public function val(OperandInterface $operand)
    {
        return new Operand(Operand::VALUE, $operand);
    }

    /**
     * @param OperandInterface $operand operand
     *
     * @return Operand
     */
    public function compl(OperandInterface $operand)
    {
        return new Operand(Operand::COMPLEMENT, $operand);
    }
}
